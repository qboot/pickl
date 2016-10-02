<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pickl\AppBundle\Controller;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Controller\ProfileController as BaseController;

/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfileController extends BaseController implements TokenAuthenticatedController
{
    /**
     * Show the user
     */
    public function showAction()
    {
        $user = $this->getUser();

        if(null === $user)
            return $this->redirectToRoute('fos_user_security_login');

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->redirectToRoute(
            'pickl_app_user_view',
            array(
                'username' => $user->getUsername()
            )
        );
    }

    /**
     * Edit the user
     */
    public function editAction(Request $request)
    {
       return $this->redirectToRoute(
           'pickl_app_user_edit'
       );
    }
}
