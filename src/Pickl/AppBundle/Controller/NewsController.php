<?php

namespace Pickl\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Pickl\AppBundle\Entity\News;
use Pickl\AppBundle\Form\NewsType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// précédemment héritait de Controller
class NewsController extends Controller implements TokenAuthenticatedController
{
    // WRITE A NEWS
    public function writeNewsAction($slug, Request $request)
    {
        $user = $this->getUser();

        if($user === null)
            return $this->redirectToRoute('fos_user_security_login');

        $em = $this->getDoctrine()->getManager();
        $projectRepository = $em->getRepository('PicklAppBundle:Project');
        $project = $projectRepository->findWithSlug($slug);

        if($project === null)
            throw new NotFoundHttpException('Project with slug "'.$slug.'" not found');

        if(($user->getId() !== $project->getUser()->getId()) && !($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')))
            throw new Exception('You can only write news for your own projects, thanks');

        $news = new News();

        $form = $this->get('form.factory')->create(NewsType::class, $news);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $news->setProject($project);

            $em->persist($news);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'News '.$news->getTitle().' added !');

            return $this->redirectToRoute(
                'pickl_app_project_view',
                array(
                    "slug" => $project->getSlug()
                )
            );
        }

        // modif
        return $this->render('PicklAppBundle:News:index.html.twig', array(
            'form' => $form->createView(),
            'project' => $project
        ));
    }

    // EDIT A NEWS
    public function editNewsAction($slug, $id, Request $request)
    {
        $user = $this->getUser();

        if($user === null)
            return $this->redirectToRoute('fos_user_security_login');

        $em = $this->getDoctrine()->getManager();
        $projectRepository = $em->getRepository('PicklAppBundle:Project');
        $project = $projectRepository->findWithSlug($slug);

        if($project === null)
            throw new NotFoundHttpException('Project with slug "'.$slug.'" not found');

        $newsRepository = $em->getRepository('PicklAppBundle:News');
        $news = $newsRepository->find($id);

        if($news === null)
            throw new NotFoundHttpException('News with id "'.$id.'" not found');

        if(($user->getId() !== $project->getUser()->getId()) && !($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')))
            throw new Exception('You can only edit news from your own projects');

        $form = $this->get('form.factory')->create(NewsType::class, $news);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'News '.$news->getTitle().' updated !');

            return $this->redirectToRoute(
                'pickl_app_project_view',
                array(
                    "slug" => $project->getSlug()
                )
            );
        }

        return $this->render('PicklAppBundle:News:index.html.twig',
            array(
                'form' => $form->createView(),
                'project' => $project,
                'news' => $news
            )
        );
    }

    // DELETE A NEWS
    public function deleteNewsAction($slug, $id, Request $request) {
        $user = $this->getUser();

        if (null === $user)
            return $this->redirectToRoute('fos_user_security_login');

        $em = $this->getDoctrine()->getManager();

        $projectRepository = $em->getRepository('PicklAppBundle:Project');
        $project = $projectRepository->findWithSlug($slug);

        if (null == $project)
            throw new NotFoundHttpException('Project "' . $slug . '" not found');

        $newsRepository = $em->getRepository('PicklAppBundle:News');
        $news = $newsRepository->find($id);

        if(null === $news)
            throw new NotFoundHttpException('News with id "'.$id.'" not found');

        if(($user->getId() !== $project->getUser()->getId()) && !($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')))
            throw new Exception('You can only delete news from your own projects');

        $form = $this->createFormBuilder()->getForm();

        if($form->handleRequest($request)->isValid()) {
            $em->remove($news);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info','News deleted !');

            return $this->redirectToRoute(
                'pickl_app_project_view',
                array(
                    'slug' => $project->getSlug()
                )
            );
        }

        return $this->render(
            'PicklAppBundle:News:delete.html.twig',
            array(
                'news' => $news,
                'form' => $form->createView()
            )
        );
    }
}