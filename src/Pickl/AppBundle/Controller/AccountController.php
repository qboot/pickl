<?php

namespace Pickl\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Config\Definition\Exception\Exception;

class AccountController extends Controller implements TokenAuthenticatedController  {

    // GET ALL PROJECTS FROM THE CURRENT USER
    public function getMyProjectsAction($page) {

        // logged user
        $user = $this->getUser();

        if(null === $user)
            return $this->redirectToRoute('fos_user_security_login');

        if($page < 1)
            throw new NotFoundHttpException('Page number "'.$page.'" not found');

        $nbPerPage = $this->container->getParameter('nb_projects_per_page_account');

        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('PicklAppBundle:Project')->findProjectsOfUser($user->getUsername(), $page, $nbPerPage);
        $nbPages = ceil(count($projects)/$nbPerPage);

        if($page > $nbPages && $nbPages != 0)
            throw new Exception('Page number "'.$page.'" not found');

        return $this->render(
            'PicklAppBundle:Account:myProjects.html.twig',
            array(
                'projects' => $projects,
                'nbPages' => $nbPages,
                'page' => $page
            )
        );
    }

    // GET ALL LIKES FROM THE CURRENT USER
    public function getMyLikesAction($page) {

        // logged user
        $user = $this->getUser();

        if(null === $user)
            return $this->redirectToRoute('fos_user_security_login');

        if($page < 1)
            throw new NotFoundHttpException('Page number "'.$page.'" not found');

        $nbPerPage = $this->container->getParameter('nb_likes_per_page_account');

        $em = $this->getDoctrine()->getManager();
        $likes = $em->getRepository('PicklAppBundle:Favorite')->findFavsOfUser($user->getUsername(), $page, $nbPerPage);
        $nbPages = ceil(count($likes)/$nbPerPage);

        if($page > $nbPages && $nbPages != 0)
            throw new Exception('Page number "'.$page.'" not found');

        return $this->render(
            'PicklAppBundle:Account:myLikes.html.twig',
            array(
                'likes' => $likes,
                'nbPages' => $nbPages,
                'page' => $page
            )
        );
    }

    // GET ALL COMMENTS FROM THE CURRENT USER
    public function getMyCommentsAction($page){

        // logged user
        $user = $this->getUser();

        if(null === $user)
            return $this->redirectToRoute('fos_user_security_login');

        if($page < 1)
            throw new NotFoundHttpException('Page number "'.$page.'" not found');

        $nbPerPage = $this->container->getParameter('nb_comments_per_page_account');

        $em = $this->getDoctrine()->getManager();
        $comments = $em->getRepository('PicklAppBundle:Comment')->findCommentsOfUser($user->getUsername(), $page, $nbPerPage);
        $nbPages = ceil(count($comments)/$nbPerPage);

        if($page > $nbPages && $nbPages != 0)
            throw new Exception('Page number "'.$page.'" not found');

        return $this->render(
            'PicklAppBundle:Account:myComments.html.twig',
            array(
                'comments' => $comments,
                'nbPages' => $nbPages,
                'page' => $page
            )
        );
    }

    // GET ALL CONTRIBUTIONS FROM THE CURRENT USER
    public function getMyContributionsAction($page){

        // logged user
        $user = $this->getUser();

        if(null === $user)
            return $this->redirectToRoute('fos_user_security_login');

        if($page < 1)
            throw new NotFoundHttpException('Page number "'.$page.'" not found');

        $nbPerPage = $this->container->getParameter('nb_contributions_per_page_account');

        $em = $this->getDoctrine()->getManager();
        $contributions = $em->getRepository('PicklAppBundle:Contribution')->findContributionsOfUser($user->getUsername(), $page, $nbPerPage);
        $nbPages = ceil(count($contributions)/$nbPerPage);

        if($page > $nbPages && $nbPages != 0)
            throw new Exception('Page number "'.$page.'" not found');

        return $this->render(
            'PicklAppBundle:Account:myContributions.html.twig',
            array(
                'contributions' => $contributions,
                'nbPages' => $nbPages,
                'page' => $page
            )
        );
    }

    // GET ALL ACTIVITY FROM THE CURRENT USER
    public function getMyActivityAction()
    {
        // logged user
        $user = $this->getUser();

        if(null === $user)
            return $this->redirectToRoute('fos_user_security_login');


        $picklActivities = $this->container->get('pickl_app.activities');

        $activities = $picklActivities->findActivities($user);

        $picklPercentage = $this->container->get('pickl_app.percentage');
        $percentage = $picklPercentage->getPercentage($user);

        return $this->render(
            'PicklAppBundle:Account:myActivity.html.twig',
            array(
                'activities' => $activities,
                'percentage' => $percentage
            )
        );
    }

    // CURRENT USER'S DASHBOARD
    public function getDashboardAction() {
        // logged user
        $user = $this->getUser();

        if(null === $user)
            return $this->redirectToRoute('fos_user_security_login');

        $limit = $this->container->getParameter('nb_each_account_dashboard');

        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('PicklAppBundle:Project')->findProjectsOfUserWithLimit($user->getUsername(), $limit);
        $likes = $em->getRepository('PicklAppBundle:Favorite')->findFavsOfUserWithLimit($user->getUsername(), $limit);
        $contributions = $em->getRepository('PicklAppBundle:Contribution')->findContributionsOfUserWithLimit($user->getUsername(), $limit);
        $comments = $em->getRepository('PicklAppBundle:Comment')->findCommentsOfUserWithLimit($user->getUsername(), $limit);

        $picklActivities = $this->container->get('pickl_app.activities');
        $activities = $picklActivities->findActivities($user, 10);

        $picklPercentage = $this->container->get('pickl_app.percentage');
        $percentage = $picklPercentage->getPercentage($user);

        return $this->render(
            'PicklAppBundle:Account:dashboard.html.twig',
            array(
                'projects' => $projects,
                'likes' => $likes,
                'contributions' => $contributions,
                'comments' => $comments,
                'activities' => $activities,
                'percentage' => $percentage
            )
        );
    }
}