<?php

namespace Pickl\AppBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Pickl\AppBundle\Entity\Project;
use Pickl\AppBundle\Form\ProjectType;
use Pickl\AppBundle\Form\ProjectEditType;
use Symfony\Component\HttpFoundation\Request;
use Pickl\AppBundle\Entity\Favorite;
use Pickl\AppBundle\Entity\Contribution;
use Pickl\AppBundle\Form\ContributionType;
use Pickl\AppBundle\Entity\Comment;
use Pickl\AppBundle\Form\CommentType;
use Pickl\AppBundle\Entity\News;
use Pickl\AppBundle\Form\NewsType;

class ProjectController extends Controller implements TokenAuthenticatedController  {

    // VIEW OF A SINGLE PROJECT
    public function viewAction($slug, Request $request) {
        $em = $this->getDoctrine()->getManager();

        $projectRepository = $em->getRepository('PicklAppBundle:Project');
        $project = $projectRepository->findWithSlug($slug);

        if(null === $project || !$project->getPublished())
            throw new NotFoundHttpException("Project with name \"".$slug."\" not found");

        $contributors = array();

        foreach($project->getContributions() as $contribution)
        {
            $contributors[] = $contribution->getUser();
        }

        $contributorsList = array();

        if($contributors !== array()) {

            foreach($contributors as $contributor)
            {
                $match = false;
                foreach($contributorsList as $contributorTemp)
                {
                    if($contributor == $contributorTemp)
                        $match = true;
                }

                if(!$match)
                    $contributorsList[] = $contributor;
            }
        }

        /**********************
         *  AJOUT DU FORMULAIRE DE COMMENTAIRE
         *
         *********************/

        // on déclare le formulaire à null pour les anonymes et non connectés
        $formComment = $this->get('form.factory')->create();

        $user = $this->getUser();

        if($user !== null)
        {
            $comment = new Comment();
            $comment->setProject($project);
            $comment->setUser($user);

            $formComment = $this->get('form.factory')->create(CommentType::class, $comment);

            if ($request->isMethod('POST') && $formComment->handleRequest($request)->isValid()) {

                $em->persist($comment);
                $em->flush();

                $request->getSession()->getFlashBag()->add('info', 'Comment added to "' . $project->getSlug() . '" !');

                return $this->redirectToRoute(
                    'pickl_app_project_view',
                    array(
                        'slug' => $project->getSlug()
                    )
                );
            }
        }

        /**********************
         *  FIN DU FORMULAIRE DE COMMENTAIRE
         *
         *********************/

        /**********************
         *  AJOUT DU FORMULAIRE DE NEWS
         *
         *********************/

        // on déclare le formulaire à null pour les anonymes et non connectés
        $formNews = $this->get('form.factory')->create();

        if($user !== null) {

            $news = new News();

            $formNews = $this->get('form.factory')->create(NewsType::class, $news);

            if($request->isMethod('POST') && $formNews->handleRequest($request)->isValid()) {
                $news->setProject($project);

                $em->persist($news);
                $em->flush();

                $request->getSession()->getFlashBag()->add('info', 'News ' . $news->getTitle() . ' added !');

                return $this->redirectToRoute(
                    'pickl_app_project_view',
                    array(
                        "slug" => $project->getSlug()
                    )
                );
            }
        }

        /**********************
         *  FIN DU FORMULAIRE DE NEWS
         *
         *********************/

        $commentRepository = $em->getRepository('PicklAppBundle:Comment');
        $commentByDate = $commentRepository->findByDate($slug);

        return $this->render(
            'PicklAppBundle:Project:view.html.twig',
            array(
                'project' => $project,
                'contributors' => $contributorsList,
                'formComment' => $formComment->createView(),
                'formNews' => $formNews->createView(),
                'commentDate' => $commentByDate
            )
        );
    }

    // ADD A PROJECT
    public function addAction(Request $request) {

        $user = $this->getUser();

        if(null === $user)
            return $this->redirectToRoute('fos_user_security_login');

        $project = new Project();

        $project->setUser($user);

        $form = $this->get('form.factory')->create(ProjectType::class, $project);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();

            if($project->getCounterparts() !== null) {
                $counterparts = new ArrayCollection();

                foreach($project->getCounterparts() as $counterpart) {
                    $counterparts->add($counterpart);
                    $project->removeCounterpart($counterpart);
                }
            }

            $em->persist($project);
            $em->flush();

            if($project->getCounterparts() !== null) {
                foreach ($counterparts as $counterpart) {
                    $counterpart->setProject($project);
                    $em->persist($counterpart);
                }

                $em->flush();
            }

            $request->getSession()->getFlashBag()->add('info','Project "'.$project->getSlug().'" saved !');

            if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                return $this->redirectToRoute('pickl_app_admin_projects');

            return $this->redirectToRoute('pickl_app_my_projects');
        }

        return $this->render(
            'PicklAppBundle:Project:add.html.twig',
            array('form' => $form->createView())
        );
    }

    // DELETE A PROJECT
    public function deleteAction($slug, Request $request)
    {
        $user = $this->getUser();

        if(null === $user)
            return $this->redirectToRoute('fos_user_security_login');

        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository('PicklAppBundle:Project')->findWithSlug($slug);

        if(null === $project)
            throw new NotFoundHttpException("Project with name \"".$slug."\" not found");

        if(($user->getId() !== $project->getUser()->getId()) && !($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')))
            throw new Exception('You can only delete your own projects, thanks');

        if($project->getPublished() && !($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')))
            throw new Exception('You can\'t delete published projects !');

        $form = $this->createFormBuilder()->getForm();

        if($form->handleRequest($request)->isValid()) {
            $counterparts = $project->getCounterparts();

            foreach($counterparts as $counterpart)
            {
                $em->remove($counterpart);
            }
            $em->flush();

            $em->remove($project);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info','Project "'.$slug.'" deleted !');

            if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                return $this->redirectToRoute('pickl_app_admin_projects');

            return $this->redirectToRoute('pickl_app_my_projects');
        }

        return $this->render(
            'PicklAppBundle:Project:delete.html.twig',
            array(
                'project' => $project,
                'form' => $form->createView()
            )
        );
    }

    // EDIT A PROJECT
    public function editAction($slug, Request $request)
    {
        $user = $this->getUser();

        if(null === $user)
            return $this->redirectToRoute('fos_user_security_login');

        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository('PicklAppBundle:Project')->findWithSlug($slug);

        if(null === $project)
            throw new NotFoundHttpException("Project with name \"".$slug."\" not found");

        if(($user->getId() !== $project->getUser()->getId()) && !($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')))
            throw new Exception('You can only edit your own projects, thanks');

        $project->setDuration($project->getStartingDate()->diff($project->getEndingDate())->format('%a'));

        $form = $this->get('form.factory')->create(ProjectEditType::class, $project);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();

            // On récupère toutes les contreparties du projet présentes en BDD

            $counterpartsInDB = $em->getRepository('PicklAppBundle:Counterpart')->findAllWithProjectId($project->getId());

            // si le formulaire renvoie des contreparties
            // on les supprime du projet
            // on les ajoute dans un tableau d'objets

            if(!empty($counterpartsInDB)) {
                foreach($counterpartsInDB as $counterpartInDB)
                {
                    $em->remove($counterpartInDB);
                }
            }

            $counterparts = $project->getCounterparts();

            if(!empty($counterparts)) {
                foreach($counterparts as $counterpart)
                {
                    $counterpart->setProject($project);
                    $em->persist($counterpart);
                }
            }

            // on ajoute l'objet modifié en bdd
            $em->flush();

            $request->getSession()->getFlashBag()->add('info','Project "'.$project->getSlug().'" saved !');

            if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
                return $this->redirectToRoute('pickl_app_admin_projects');

            return $this->redirectToRoute('pickl_app_my_projects');
        }

        return $this->render(
            'PicklAppBundle:Project:edit.html.twig',
            array(
                'form' => $form->createView(),
                'project' => $project
            )
        );

    }

    // LIKE A PROJECT
    public function addLikeAction($slug, $dashboard, Request $request) {

        $user = $this->getUser();

        if(null === $user)
            return $this->redirectToRoute('fos_user_security_login');

        $em = $this->getDoctrine()->getManager();

        $projectRepository = $em->getRepository('PicklAppBundle:Project');
        $project = $projectRepository->findWithSlug($slug);

        if(null === $project)
            throw new NotFoundHttpException('Project "'.$slug.'" not found');

        if(!$project->getPublished())
            throw new Exception('Project "'.$project->getTitle().'" is closed, come back later !');

        $favoriteRepository = $em->getRepository('PicklAppBundle:Favorite');
        $favorite = $favoriteRepository->findFav($user->getId(), $project->getId());

        if(null === $favorite) {
            $favorite = new Favorite();

            $favorite->setUser($user);
            $favorite->setProject($project);

            $em->persist($favorite);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info','Project "'.$project->getTitle().'" liked !');

            if($dashboard == 'true') {
                return $this->redirectToRoute(
                    'pickl_app_my_account'
                );
            }

            return $this->redirectToRoute(
                'pickl_app_project_view',
                array('slug' => $project->getSlug())
            );

        } else{

            $em->remove($favorite);

            $em->flush();

            $request->getSession()->getFlashBag()->add('info','Like on "'.$project->getTitle().'" removed !');

            if($dashboard == 'true') {
                return $this->redirectToRoute(
                    'pickl_app_my_account'
                );
            }

            return $this->redirectToRoute(
                'pickl_app_project_view',
                array('slug' => $project->getSlug())
            );
        }
    }

    // SUPPORT A PROJECT
    public function addContributionAction($slug, Request $request) {

        $user = $this->getUser();

        if(null === $user)
            return $this->redirectToRoute('fos_user_security_login');

        $em = $this->getDoctrine()->getManager();

        $projectRepository = $em->getRepository('PicklAppBundle:Project');
        $project = $projectRepository->findWithSlug($slug);

        $counterpartRepository = $em->getRepository('PicklAppBundle:Counterpart');

        if(null === $project)
            throw new NotFoundHttpException('Project "'.$slug.'" not found');

        if(!$project->isOpened())
            throw new Exception('Project "'.$project->getTitle().'" is closed, come back later !');

        $contribution = new Contribution();

        $contribution->setUser($user);
        $contribution->setProject($project);

        $form = $this->get('form.factory')->create(ContributionType::class, $contribution);


        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $price = $contribution->getAmount();

            $counterpart = $counterpartRepository->findWithPrice($project->getId(),$price);

            //Si l'utilisateur donne assez pour avoir droit à une contrepartie (counterpart != null)
            if($counterpart != null)
            {
                //si le nombre de contrepartie actuel est inférieur au nombre total de contrepartie
                if($counterpart->getNbCounterparts() < $counterpart->getMaxCounterparts())
                {
                    $counterpart->setNbCounterparts($counterpart->getNbCounterparts() + 1);
                    $contribution->setCounterpart($counterpart);
                    $em->persist($counterpart);
                }
            }

            $em->persist($contribution);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info','Support on "'.$project->getTitle().'" succeed, thanks !');

            return $this->redirectToRoute('pickl_app_my_contributions');
        }

        return $this->render(
            'PicklAppBundle:Project:addContribution.html.twig',
            array(
                'form' => $form->createView(),
                'project' => $project
            )
        );

    }



    // MEHDI


    public function getNewsAjaxAction($slug){

        $em = $this->getDoctrine()->getManager();

        $projectRepository = $em->getRepository('PicklAppBundle:Project');
        $project = $projectRepository->findWithSlug($slug);


        return $this->render('PicklAppBundle:Project:news.html.twig',
            array('project' => $project));
    }


    public function getCommentsAjaxAction($slug, Request $request){

        $em = $this->getDoctrine()->getManager();

        $projectRepository = $em->getRepository('PicklAppBundle:Project');
        $project = $projectRepository->findWithSlug($slug);

        $commentRepository = $em->getRepository('PicklAppBundle:Comment');
        $commentByDate = $commentRepository->findByDate($slug);

        $formComment = $this->get('form.factory')->create();

        $user = $this->getUser();

        if($user !== null)
        {
            $comment = new Comment();
            $comment->setProject($project);
            $comment->setUser($user);

            $formComment = $this->get('form.factory')->create(CommentType::class, $comment);

            if ($request->isMethod('POST') && $formComment->handleRequest($request)->isValid()) {

                $em->persist($comment);
                $em->flush();

                $request->getSession()->getFlashBag()->add('info', 'Comment added to "' . $project->getSlug() . '" !');

                return $this->redirectToRoute(
                    'pickl_app_project_view',
                    array(
                        'slug' => $project->getSlug()
                    )
                );
            }
        }


        return $this->render('PicklAppBundle:Project:comments.html.twig',
            array(
                'commentDate' => $commentByDate,
                'project' => $project,
                'formComment' => $formComment->createView()
            ));
    }

    public function getDonatorsAjaxAction($slug){

        $em = $this->getDoctrine()->getManager();

        $projectRepository = $em->getRepository('PicklAppBundle:Project');
        $project = $projectRepository->findWithSlug($slug);

        if(null === $project || !$project->getPublished())
            throw new NotFoundHttpException("Project with name \"".$slug."\" not found");

        $contributors = array();

        foreach($project->getContributions() as $contribution)
        {
            $contributors[] = $contribution->getUser();
        }

        $contributorsList = array();

        if($contributors !== array()) {

            foreach($contributors as $contributor)
            {
                $match = false;
                foreach($contributorsList as $contributorTemp)
                {
                    if($contributor == $contributorTemp)
                        $match = true;
                }

                if(!$match)
                    $contributorsList[] = $contributor;
            }
        }

        return $this->render('PicklAppBundle:Project:donators.html.twig',
            array(
                'contributors' => $contributorsList,
                'project' => $project));
    }

    public function getViewAjaxAction($slug){

        $em = $this->getDoctrine()->getManager();

        $projectRepository = $em->getRepository('PicklAppBundle:Project');
        $project = $projectRepository->findWithSlug($slug);


        return $this->render('PicklAppBundle:Project:viewajax.html.twig',
            array('project' => $project));
    }

    public function reportAjaxAction($slug) {
        $em = $this->getDoctrine()->getManager();

        $projectRepository = $em->getRepository('PicklAppBundle:Project');
        $project = $projectRepository->findWithSlug($slug);

        $project->setReported(true);
        $em->persist($project);
        $em->flush();

        return $this->redirectToRoute('pickl_app_project_view', array('slug' => $slug));
    }

}