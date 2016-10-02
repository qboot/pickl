<?php

namespace Pickl\AppBundle\Controller;

use Pickl\AppBundle\Entity\Rank;
use Pickl\AppBundle\Form\RankType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Pickl\AppBundle\Form\AdminUserType;
use Pickl\AppBundle\Entity\Reward;
use Pickl\AppBundle\Form\AdminRewardType;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Pickl\AppBundle\Entity\Newsletter;
use Pickl\AppBundle\Form\NewsletterType;

// précédemment héritait de Controller
class AdminController extends Controller implements TokenAuthenticatedController
{
    // DASHBOARD ADMIN
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $limit = $this->container->getParameter('nb_each_admin_dashboard');

        $users = $em->getRepository('PicklAppBundle:User')->findRecentsUsers($limit);
        $rewards = $em->getRepository('PicklAppBundle:Reward')->findLastRewards($limit);
        $ranks = $em->getRepository('PicklAppBundle:Rank')->findBiggestRanks($limit);
        $projects = $em->getRepository('PicklAppBundle:Project')->findRecentsProjects($limit);


        /* NEWSLETTER */
        /**************/

        // headers
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-Type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'To: Subscribers <subscribers@pickl.com>' . "\r\n";
        $headers .= 'From: Pickl Team <team@pickl.com>' . "\r\n";

        // entitymanager / on récup tous les mails de la BDD et on crée un objet vide newsletter
        $em = $this->getDoctrine()->getManager();
        $newsletterRepository = $em->getRepository('PicklAppBundle:Newsletter');
        $mails = $newsletterRepository->findAll();
        $newsletter = new Newsletter();

        // on crée le formulaire
        $form = $this->get('form.factory')->create(NewsletterType::class, $newsletter);

        // si les mails sont vides inutile d'aller plus loin on return
        if(empty($mails))
        {
            $request->getSession()->getFlashBag()->add('info','No subscriber for newsletter in DB :(');

            return $this->render(
                'PicklAppBundle:Admin:index.html.twig',
                array(
                    'users' => $users,
                    'rewards' => $rewards,
                    'ranks' => $ranks,
                    'projects' => $projects,
                    'form' => $form->createView(),
                )
            );
        }

        // on boucle pour mettre tous les mails dans un $to
        $to = '';
        foreach ($mails as $mail) {
            $to .= $mail->getMail() . ", ";
        }
        $to .= "mehdi.lafenetre@gmail.com, perso.quentinbrunet@gmail.com";

        // si la requete est valide
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            // on récupère le message et le sujet
            $content = $newsletter->getMessage();
            $title = $newsletter->getSubject();

            // template html on pense a encodé les caractères spéciaux html '"& etc...
            $message =
                '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
                <html lang="en" style="width:100%;height:100%;overflow:hidden; margin:0;padding:0;">
                    <head>
                        <meta charset="UTF-8" />
                    </head>
                    <body style="width:100%;height:100%;overflow:hidden; margin:0;padding:0;background-color:rgb(245,245,245)">
                        <table style="margin-left:auto; margin-right:auto; width:800px; text-align:center; font-family:sans-serif; border-collapse:collapse;background-color:white;">
                            <tr>
                                <td style="color:white; background-color:rgb(116,214,136); height:100px; font-size:36px; font-weight:400;" colspan="3">NEWSLETTER PICKL</td>
                            </tr>
                            <tr>
                                <td style="height:50px;" colspan="3"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="font-size:30px; border-bottom:5px solid rgb(245,245,245); border-top:5px solid rgb(245,245,245); padding-top:30px; padding-bottom:30px;">'.htmlspecialchars($title, ENT_QUOTES).'</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="height:50px;" colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:justify;line-height:20px; padding-right:15px;padding-left:15px;">﻿‌'.htmlspecialchars($content, ENT_QUOTES).'</td>
                            </tr>
                            <tr>
                                <td style="height:50px;" colspan="3"></td>
                            </tr>
                            <tr>
                                <td style="text-align:center;padding-left:15px;">
                                    <a style="display:block; color:white;background-color:rgb(116,214,136);width:200px;margin-left:auto;margin-right:auto; text-decoration:none; cursor:pointer; height:40px;line-height:40px;" href="http://lab.quentinbrunet.com/">VISIT WEBSITE</a>
                                </td>
                                <td style="text-align:center">
                                    <a style="display:block; color:white;background-color:rgb(116,214,136);width:200px;margin-left:auto;margin-right:auto; text-decoration:none; cursor:pointer; height:40px;line-height:40px;" href="http://lab.quentinbrunet.com/explore">EXPLORE PROJECTS</a>
                                </td>
                                <td style="text-align:center;padding-right:15px;">
                                    <a style="display:block; color:white;background-color:rgb(116,214,136);width:200px;margin-left:auto;margin-right:auto; text-decoration:none; cursor:pointer; height:40px;line-height:40px;" href="http://lab.quentinbrunet.com/account/project/add">START A CAMPAIGN</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="height:50px;" colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="font-style:italic;color:rgb(100,100,100);">Pickl is a crowdfunding website where small ideas become incredible successful stories.</td>
                            </tr>
                            <tr>
                                <td style="height:50px;" colspan="3"></td>
                            </tr>
                            <tr>
                                <td style="background-color:rgb(21,22,23); padding-top:15px; height:100px;" colspan="3"><img src="http://lab.quentinbrunet.com/pickl/logo.png" width="150" alt="Pickl &copy;" /></td>
                            </tr>
                        </table>
                    </body>
                </html>';

            // on ajoute un sujet perso
            $subject = 'Pickl Newsletter';

            // qqs tests avant envoi (a delete ou quoter)
            //$testmail = "To : " . $to . " / Subject : " . $subject . " / Message : " . $message . " / Headers : " . $headers;
            //dump($testmail);
            //die();

            // on envoie le message
            mail($to, $subject, $message, $headers);

            // on vide les champs du formulaire
            $newsletter = new Newsletter();
            $form = $this->get('form.factory')->create(NewsletterType::class, $newsletter);

            // on ajoute un message de succès
            $request->getSession()->getFlashBag()->add('info','Well done! Newsletter sent to '.count($mails).' people');

        }

        // on redirige vers le dashboard admin

        /**************/
        /* NEWSLETTER */

        return $this->render(
            'PicklAppBundle:Admin:index.html.twig',
            array(
                'users' => $users,
                'rewards' => $rewards,
                'ranks' => $ranks,
                'projects' => $projects,
                'form' => $form->createView(),
            )
        );
    }

    // USERS LIST ADMIN
    public function getUsersAction($page)
    {
        if($page < 1)
            throw new NotFoundHttpException('Page number "'.$page.'" not found');

        $nbPerPage = $this->container->getParameter('nb_users_per_page_admin');

        $em = $this->getDoctrine()->getManager();

        $users = $em
            ->getRepository('PicklAppBundle:User')
            ->findAllUsers($page, $nbPerPage)
        ;

        $admins = $em
            ->getRepository('PicklAppBundle:User')
            ->findAllAdmins()
        ;

        $nbPages = ceil((count($users))/$nbPerPage);

        if($page > $nbPages && $nbPages != 0)
            throw new Exception('Page number "'.$page.'" not found');

        return $this->render(
            'PicklAppBundle:Admin:users.html.twig',
            array(
                'admins' => $admins,
                'users' => $users,
                'nbPages' => $nbPages,
                'page' => $page
            )
        );
    }

    // PROJECTS LIST ADMIN
    public function getProjectsAction($page)
    {
        if($page < 1)
            throw new NotFoundHttpException('Page number "'.$page.'" not found');

        $nbPerPage = $this->container->getParameter('nb_projects_per_page_admin');

        $projects = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('PicklAppBundle:Project')
            ->findAllProjects($page,$nbPerPage)
        ;

        $nbPages = ceil(count($projects)/$nbPerPage);

        if($page > $nbPages)
            throw new Exception('Page number "'.$page.'" not found');

        return $this->render(
            'PicklAppBundle:Admin:projects.html.twig',
            array(
                'projects' => $projects,
                'nbPages' => $nbPages,
                'page' => $page
            )
        );
    }

    // REWARDS LIST ADMIN
    public function getRewardsAction()
    {
        $rewards = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('PicklAppBundle:Reward')
            ->findAll();

        return $this->render(
            'PicklAppBundle:Admin:rewards.html.twig',
            array(
                "rewards" => $rewards
            )
        );
    }

    // RANKS LIST ADMIN
    public function getRanksAction()
    {
        $ranks = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('PicklAppBundle:Rank')
            ->findAllOrderByLevelMin()
        ;

        return $this->render(
            'PicklAppBundle:Admin:ranks.html.twig',
            array(
                "ranks" => $ranks
            )
        );
    }

    // PROMOTE A USER TO ROLE ADMIN
    public function promoteAction($username, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $userRepository = $em->getRepository('PicklAppBundle:User');
        $user = $userRepository->findWithUsername($username);

        if(null === $user)
            throw new NotFoundHttpException('User with username "'.$user->getUsername().'" not found');

        if(!$user->hasRole('ROLE_ADMIN')) {
            $user->addRole('ROLE_ADMIN');
            $em->flush();
            $request->getSession()->getFlashBag()->add('info','User "'.$user->getUsername().'" promoted to admin role !');
        }

        return $this->redirectToRoute(
            'pickl_app_admin_users'
        );
    }

    // DEMOTE AN ADMIN TO ROLE USER
    public function demoteAction($username, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $userRepository = $em->getRepository('PicklAppBundle:User');
        $user = $userRepository->findWithUsername($username);

        if(null === $user)
            throw new NotFoundHttpException('User with username "'.$user->getUsername().'" not found');

        if($user->hasRole('ROLE_ADMIN')) {
            $user->removeRole('ROLE_ADMIN');
            $em->flush();
            $request->getSession()->getFlashBag()->add('info','User "'.$user->getUsername().'" demoted to user role !');
        }

        return $this->redirectToRoute(
            'pickl_app_admin_users'
        );
    }

    // ADD A NEW PROJECT
    public function addProjectAction(Request $request) {
        return  $this->forward('PicklAppBundle:Project:add', array(
            'request'  => $request
        ));
    }

    // EDIT AN EXISTING PROJECT
    public function editProjectAction($slug, Request $request)
    {
        return  $this->forward('PicklAppBundle:Project:edit', array(
            'slug' => $slug,
            'request'  => $request
        ));
    }

    // DELETE A PROJECT
    public function deleteProjectAction(Request $request, $slug)
    {
        return  $this->forward('PicklAppBundle:Project:delete', array(
            'request' => $request,
            'slug' => $slug
        ));
    }

    // ADD A USER - inspired by registerAction in RegistrationController (FOSUserBundle)
    public function addUserAction(Request $request)
    {
        $formFactory = $this->get('fos_user.registration.form.factory');

        $userManager = $this->get('fos_user.user_manager');

        $dispatcher = $this->get('event_dispatcher');

        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            $userManager->updateUser($user);

            $request->getSession()->getFlashBag()->add('info','New user  "'.$user->getUsername().'" created !');

            return $this->redirectToRoute('pickl_app_admin_users');
        }

        return $this->render('FOSUserBundle:Admin:addUser.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    // EDIT A USER
    public function editUserAction($username, Request $request)
    {
        return  $this->forward('PicklAppBundle:User:edit', array(
            'request' => $request,
            'username' => $username
        ));
    }

    // DELETE A USER
    public function deleteUserAction($username, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('PicklAppBundle:User');

        $user = $userRepository->findWithUsername($username);

        if(null === $user)
            throw new NotFoundHttpException("User with username \"".$username."\" not found");

        $form = $this->createFormBuilder()->getForm();

        if($form->handleRequest($request)->isValid()) {
            $em->remove($user);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info','User "'.$username.'" deleted !');

            return $this->redirectToRoute('pickl_app_admin_users');
        }

        return $this->render(
            'PicklAppBundle:Admin:deleteUser.html.twig',
            array(
                'user' => $user,
                'form' => $form->createView()
            )
        );
    }

    // ADD A RANK
    public function addRankAction(Request $request) {
        $rank = new Rank();

        $form = $this->get('form.factory')->create(RankType::class, $rank);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($rank);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info','Rank "'.$rank->getName().'" added !');

            return $this->redirectToRoute('pickl_app_admin_ranks');
        }

        return $this->render(
            'PicklAppBundle:Admin:addRank.html.twig',
            array('form' => $form->createView())
        );
    }

    // EDIT A RANK
    public function editRankAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();

        $rank = $em->getRepository('PicklAppBundle:Rank')->find($id);

        if(null === $rank)
            throw new NotFoundHttpException('Rank with id "'.$id.'" not found');

        $form = $this->get('form.factory')->create(RankType::class, $rank);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em->flush();

            $request->getSession()->getFlashBag()->add('info','Rank "'.$rank->getName().'" updated !');

            return $this->redirectToRoute('pickl_app_admin_ranks');
        }

        return $this->render(
            'PicklAppBundle:Admin:addRank.html.twig',
            array(
                'form' => $form->createView(),
                'rank' => $rank
            )
        );
    }

    // DELETE A RANK
    public function deleteRankAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('PicklAppBundle:User');

        $rank = $em->getRepository('PicklAppBundle:Rank')->find($id);

        if(null === $rank)
            throw new NotFoundHttpException("Rank with id \"".$id."\" not found");

        $form = $this->createFormBuilder()->getForm();

        if($form->handleRequest($request)->isValid()) {

            $users = $rank->getUsers();

            if(count($users) > 0) {
                $request->getSession()->getFlashBag()->add('info','Can\'t delete rank "'.$rank->getName().'" because some users belong to it !');
                return $this->redirectToRoute('pickl_app_admin_ranks');
            }

            // on enleve le rang
            $em->remove($rank);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info','Rank "'.$rank->getName().'" deleted !');

            return $this->redirectToRoute('pickl_app_admin_ranks');
        }

        return $this->render(
            'PicklAppBundle:Admin:deleteRank.html.twig',
            array(
                'rank' => $rank,
                'form' => $form->createView()
            )
        );
    }


    // A SUPPRIMER QUAND ON SERA SURS QUE CELA FONCTIONNE DANS LA FONCTION INDEXACTION PLUS HAUT
    // WRITE THE NEWSLETTER
    public function writeNewsletterAction(Request $request)
    {
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-Type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'To: Subscribers <subscribers@pickl.com>' . "\r\n";
        $headers .= 'From: Pickl Team <team@pickl.com>' . "\r\n";
        $em = $this->getDoctrine()->getManager();
        $newsletterRepository = $em->getRepository('PicklAppBundle:Newsletter');
        $mails = $newsletterRepository->findAll();
        $newsletter = new Newsletter();

        $to = '';
        foreach ($mails as $mail) {
            $to .= $mail->getMail() . ", ";
        }
        $to .= "mehdi.lafenetre@gmail.com, perso.quentinbrunet@gmail.com";
        $form = $this->get('form.factory')->create(NewsletterType::class, $newsletter);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $content = $newsletter->getMessage();
            $title = $newsletter->getSubject();

            $message =
                '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
                <html lang="en" style="width:100%;height:100%;overflow:hidden; margin:0;padding:0;">
                    <head>
                        <meta charset="UTF-8" />
                    </head>
                    <body style="width:100%;height:100%;overflow:hidden; margin:0;padding:0;background-color:rgb(245,245,245)">
                        <table style="margin-left:auto; margin-right:auto; width:800px; text-align:center; font-family:sans-serif; border-collapse:collapse;background-color:white;">
                            <tr>
                                <td style="color:white; background-color:rgb(116,214,136); height:100px; font-size:36px; font-weight:400;" colspan="3">NEWSLETTER PICKL</td>
                            </tr>
                            <tr>
                                <td style="height:50px;" colspan="3"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="font-size:30px; border-bottom:5px solid rgb(245,245,245); border-top:5px solid rgb(245,245,245); padding-top:30px; padding-bottom:30px;">'.htmlspecialchars($title, ENT_QUOTES).'</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="height:50px;" colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align:justify;line-height:20px; padding-right:15px;padding-left:15px;">﻿‌'.htmlspecialchars($content, ENT_QUOTES).'</td>
                            </tr>
                            <tr>
                                <td style="height:50px;" colspan="3"></td>
                            </tr>
                            <tr>
                                <td style="text-align:center;padding-left:15px;">
                                    <a style="display:block; color:white;background-color:rgb(116,214,136);width:200px;margin-left:auto;margin-right:auto; text-decoration:none; cursor:pointer; height:40px;line-height:40px;" href="http://lab.quentinbrunet.com/pickl/">VISIT WEBSITE</a>
                                </td>
                                <td style="text-align:center">
                                    <a style="display:block; color:white;background-color:rgb(116,214,136);width:200px;margin-left:auto;margin-right:auto; text-decoration:none; cursor:pointer; height:40px;line-height:40px;" href="http://lab.quentinbrunet.com/pickl/explore">EXPLORE PROJECTS</a>
                                </td>
                                <td style="text-align:center;padding-right:15px;">
                                    <a style="display:block; color:white;background-color:rgb(116,214,136);width:200px;margin-left:auto;margin-right:auto; text-decoration:none; cursor:pointer; height:40px;line-height:40px;" href="http://lab.quentinbrunet.com/pickl/account/project/add">START A CAMPAIGN</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="height:50px;" colspan="3"></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="font-style:italic;color:rgb(100,100,100);">Pickl is a crowdfunding website where small ideas become incredible successful stories.</td>
                            </tr>
                            <tr>
                                <td style="height:50px;" colspan="3"></td>
                            </tr>
                            <tr>
                                <td style="background-color:rgb(21,22,23); padding-top:15px; height:100px;" colspan="3"><img src="http://lab.quentinbrunet.com/pickl/logo.png" width="150" alt="Pickl &copy;" /></td>
                            </tr>
                        </table>
                    </body>
                </html>';

            $subject = 'Newsletter Pickl';

            // a delete
            $testmail = "To : " . $to . " / Subject : " . $subject . " / Message : " . $message . " / Headers : " . $headers;
            dump($testmail);
            die();

            mail($to, $subject, $message, $headers);

            $request->getSession()->getFlashBag()->add('info','Well done! Newsletter sent to '.count($mails).' people');

            return $this->redirectToRoute('pickl_app_homepage');
        }


        return $this->render('PicklAppBundle:Admin:writeNewsletter.html.twig', array(
            'form' => $form->createView(),
        ));

    }
}