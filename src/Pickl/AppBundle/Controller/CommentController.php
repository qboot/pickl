<?php

namespace Pickl\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

use Pickl\AppBundle\Entity\Comment;
use Pickl\AppBundle\Form\CommentType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CommentController extends Controller implements TokenAuthenticatedController  {

    // ADD A COMMENT
    public function addCommentAction($slug, Request $request)
    {
        $user = $this->getUser();

        if (null === $user)
            return $this->redirectToRoute('fos_user_security_login');

        $em = $this->getDoctrine()->getManager();

        $projectRepository = $em->getRepository('PicklAppBundle:Project');
        $project = $projectRepository->findWithSlug($slug);

        if (null == $project)
            throw new NotFoundHttpException('Project "' . $slug . '" not found');

        if(!$project->getPublished())
            throw new Exception('Project "'.$project->getTitle().'" is closed, come back later !');

        $comment = new Comment();
        $comment->setProject($project);
        $comment->setUser($user);

        $form = $this->get('form.factory')->create(CommentType::class, $comment);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

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

        return $this->render(
            'PicklAppBundle:Comment:index.html.twig',
            array(
                'form' => $form->createView(),
                'project' => $project
            )
        );
    }

    // EDIT A COMMENT
    public function editCommentAction($slug, $id, Request $request)
    {
        $user = $this->getUser();

        if (null === $user)
            return $this->redirectToRoute('fos_user_security_login');

        $em = $this->getDoctrine()->getManager();

        $projectRepository = $em->getRepository('PicklAppBundle:Project');
        $project = $projectRepository->findWithSlug($slug);

        if (null == $project)
            throw new NotFoundHttpException('Project "' . $slug . '" not found');


        $commentRepository = $em->getRepository('PicklAppBundle:Comment');
        $comment = $commentRepository->find($id);

        if(null === $comment)
            throw new NotFoundHttpException('Comment with id "'.$id.'" not found');

        if($comment->getUser()->getId() != $user->getId()  && !($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')))
            throw new Exception('You can only edit your own comments, thanks');

        $form = $this->get('form.factory')->create(CommentType::class, $comment);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Comment updated !');

            return $this->redirectToRoute(
                'pickl_app_project_view',
                array(
                    'slug' => $project->getSlug()
                )
            );

        }

        return $this->render(
            'PicklAppBundle:Comment:index.html.twig',
            array(
                'form' => $form->createView(),
                'comment' => $comment,
                'project' => $project
            )
        );
    }

    // DELETE A COMMENT
    public function deleteCommentAction($slug, $id, Request $request)
    {
        $user = $this->getUser();

        if (null === $user)
            return $this->redirectToRoute('fos_user_security_login');

        $em = $this->getDoctrine()->getManager();

        $projectRepository = $em->getRepository('PicklAppBundle:Project');
        $project = $projectRepository->findWithSlug($slug);

        if (null == $project)
            throw new NotFoundHttpException('Project "' . $slug . '" not found');

        $commentRepository = $em->getRepository('PicklAppBundle:Comment');
        $comment = $commentRepository->find($id);

        if(null === $comment)
            throw new NotFoundHttpException('Comment with id "'.$id.'" not found');

        if($comment->getUser()->getId() != $user->getId()  && !($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')))
            throw new Exception('You can only delete your own comments, thanks');


        $form = $this->createFormBuilder()->getForm();

        if($form->handleRequest($request)->isValid()) {
            $em->remove($comment);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info','Comment deleted !');
            return $this->redirectToRoute(
                'pickl_app_project_view',
                array(
                    'slug' => $project->getSlug()
                )
            );
        }

        return $this->render(
            'PicklAppBundle:Comment:delete.html.twig',
            array(
                'comment' => $comment,
                'form' => $form->createView()
            )
        );
    }

}