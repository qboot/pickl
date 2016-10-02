<?php

namespace Pickl\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Pickl\AppBundle\Form\UserEditType;

// pour transformer code pays en nom pays
use Symfony\Component\Intl\Intl;


class UserController extends Controller implements TokenAuthenticatedController {

    // VIEW OF A SINGLE USER
    public function viewAction($username) {

        $em = $this->getDoctrine()->getManager();

        $userRepository = $em->getRepository('PicklAppBundle:User');

        $user = $userRepository->findWithUsername($username);

        if(null === $user)
            throw new NotFoundHttpException('Utilisateur introuvable');

        $picklPercentage = $this->container->get('pickl_app.percentage');
        $percentage = $picklPercentage->getPercentage($user);

        $user->setCountry(Intl::getRegionBundle()->getCountryName($user->getCountry()));

        return $this->render(
            'PicklAppBundle:User:user.html.twig',
            array('user' => $user, 'percent' => $percentage)
        );
    }

    // EDIT CURRENT USER'S INFOS
    public function editAction(Request $request, $username = null) {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('PicklAppBundle:User');

        // si username vaut null on suit le process normal sinon c'est qu'on vient de la partie admin
        if($username === null) {
            $user = $this->getUser();
        } else {
            $user = $userRepository->findWithUsername($username);
            if($user === null)
                throw new NotFoundHttpException('User "'.$username.'" not found');
        }

        if(null === $user)
            return $this->redirectToRoute('fos_user_security_login');


        $form = $this->get('form.factory')->create(UserEditType::class, $user);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em->flush();

            $request->getSession()->getFlashBag()->add('info','Profile updated!');

            // si l'username n'est pas null on vient de la partie admin
            if($username !== null)
                return $this->redirectToRoute('pickl_app_admin_users');

            return $this->redirectToRoute(
                'pickl_app_user_view',
                array('username' => $user->getUsername())
            );
        }

        return $this->render(
            'PicklAppBundle:User:edit.html.twig',
            array(
                'form' => $form->createView(),
                'user' => $user
            )
        );
    }

    // LOGOUT
    public function redirectLogoutAction() {
        return $this->redirectToRoute('pickl_app_user_logout');
    }

    // LOGOUT BIS
    public function logoutAction() {
        return  $this->forward(
            'FOSUserBundle:Security:logout'
        );
    }

}