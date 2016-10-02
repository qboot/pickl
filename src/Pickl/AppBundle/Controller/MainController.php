<?php

namespace Pickl\AppBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Pickl\AppBundle\Entity\Newsletter;

class MainController extends Controller implements TokenAuthenticatedController  {

    // HOME PAGE
    public function indexAction() {

        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('PicklAppBundle:Category')->findAll();
        $projects = $em->getRepository('PicklAppBundle:Project')->findThreeLast();

        $ofTheDay = $this->container->get('pickl_app.of_the_day');

        $response = $ofTheDay->getProjectOfTheDayAndTrends();

        // on fait le rendu
        // trends contient 3 arrays associatifs avec sum et entity (projects)
        // ofTheDay contient 1 array associatif avec sum et entity (project
        return $this->render(
            'PicklAppBundle:Main:index.html.twig',
            array(
                'ofTheDay' => $response['ofTheDay'],
                'trends' => $response['trends'],
                'categories' => $categories,
                'projects' => $projects
            )
        );
    }

    // SEARCH PAGE
    public function searchAction(Request $request) {
        $arg = strtolower($request->query->get('q'));

        if(empty($arg))
            throw new Exception('Please type something before submit');

        $em = $this->getDoctrine()->getManager();
        $projectRepository = $em->getRepository('PicklAppBundle:Project');

        // on recup les projets qui contiennent l'arg en titre, smallDesc ou bigDesc, tags
        $projects = $projectRepository->searchWithArg($arg);

        $picklSearch = $this->container->get('pickl_app.search');

        // on organise les résultats
        $results = $picklSearch->organizeSearch($projects, $arg);

        return $this->render(
            'PicklAppBundle:Main:search.html.twig',
            array(
                'arg' => $arg,
                'results' => $results
            )
        );
    }

    // RANKING PAGE
    public function getRankingAction($limit) {
        $limit = (int) $limit;

        if($limit < 1)
            throw new NotFoundHttpException('Limit "'.$limit.'" too small');

        $em = $this->getDoctrine()->getManager();

        $users = $em
            ->getRepository('PicklAppBundle:User')
            ->findAllUsersOrderByXp()
        ;

        $tabUsers = array();
        $i = 1;
        $placeCurrentUser = false;
        foreach($users as $user) {
            if($this->getUser() !== null) {
                if($this->getUser()->getUsername() == $user->getUsername()) {
                    $placeCurrentUser = $i;
                }
            }

            $tabUsers[] = array(
                'place' => $i,
                'user' => $user
            );
            $i++;
        }

        $tabFinal = array();
        for($i = 0; $i<$limit;$i++) {
            if(isset($tabUsers[$i]))
                $tabFinal[] = $tabUsers[$i];
        }

        return $this->render(
            'PicklAppBundle:Main:ranking.html.twig',
            array(
                'users' => $tabFinal,
                'limit' => $limit,
                'placeCurrentUser' => $placeCurrentUser
            )
        );
    }


    public function getRewardsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $rewards = $em->getRepository('PicklAppBundle:Reward')->findAll();

        $projects = $em->getRepository('PicklAppBundle:Project')->findAll();
        $nbComments = count($em->getRepository('PicklAppBundle:Comment')->findAll());

        $total = 0;
        $highestPercentage = 0;
        foreach($projects as $project) {
            $total += $project->getCollectedAmount();
            if($project->getPercent() > $highestPercentage)
                $highestPercentage = $project->getPercent();
        }

        $nbProjects = count($projects);
        $highestPercentage = (int) $highestPercentage;

        return $this->render(
            'PicklAppBundle:Main:rewards.html.twig',
            array(
                'nbProjects' => $nbProjects,
                'nbComments' => $nbComments,
                'bestPercentage' => $highestPercentage,
                'totalAmount' => $total,
                'rewards' => $rewards
            )
        );
    }


    // EXPLORE PAGE
    public function exploreAction($limit) {
        $limit = (int) $limit;

        // get categories
        // get project of the day
        // get all project avec limit
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('PicklAppBundle:Category')->findAll();

        // project of the day
        $ofTheDay = $this->container->get('pickl_app.of_the_day');
        $projectOfTheDay = $ofTheDay->getProjectOfTheDayAndTrends()['ofTheDay'];

        // all projects with limit order by date desc
        $projects = $em->getRepository('PicklAppBundle:Project')->findAllProjectsWithLimit($limit);


        return $this->render(
            'PicklAppBundle:Main:explore.html.twig',
            array(
                'limit' => $limit,
                'categories' => $categories,
                'ofTheDay' => $projectOfTheDay,
                'projects' => $projects
            )
        );
    }

    // HOW IT WORKS
    public function getHowItWorksAction(){
        return $this->render(
            'PicklAppBundle:Main:howitworks.html.twig'
        );
    }

    public function newsletterAction(Request $request) {

        $referer = $request->headers->get('referer');

        $mail = $request->request->get('mail');

        if((empty($mail)))
            return $this->redirectToRoute('pickl_app_homepage');

        $em = $this->getDoctrine()->getManager();
        $found = $em->getRepository('PicklAppBundle:Newsletter')->findWithMail($mail);

        if($found === null) {
            // rien n'est en BDD, on peut ajouter
            $user = new Newsletter();
            $user->setMail($mail);

            $em->persist($user);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info','Thanks! "'.$mail.'" added to newsletter');

        } else {
            $request->getSession()->getFlashBag()->add('info','Email "'.$mail.'" already registered !');
        }

        if(!empty($referer)) {
            return $this->redirect($referer);
        } else {
            return $this->redirectToRoute('pickl_app_homepage');
        }
    }

    public function getAboutAction() {
        return $this->render(
            'PicklAppBundle:Main:about.html.twig'
        );
    }

}