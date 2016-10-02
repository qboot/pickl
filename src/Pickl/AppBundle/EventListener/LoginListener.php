<?php

namespace Pickl\AppBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\RequestStack;

/**
* Listener responsible to change the redirection at the end of the password resetting
*/
class LoginListener implements EventSubscriberInterface
{
    private $container;

    protected $request;

    public function setRequest(RequestStack $request_stack)
    {
        $this->request = $request_stack->getCurrentRequest();
    }

    public function __construct($container)
    {
    $this->container = $container;
    }

    /**
    * {@inheritDoc}
    */
    public static function getSubscribedEvents()
    {
    return array(
    FOSUserEvents::SECURITY_IMPLICIT_LOGIN => 'onLogin',
    SecurityEvents::INTERACTIVE_LOGIN => 'onLogin',
    );
    }

    public function onLogin($event)
    {
        // FYI
        if ($event instanceof UserEvent) {
            $user = $event->getUser();
        }
        if ($event instanceof InteractiveLoginEvent) {
            $user = $event->getAuthenticationToken()->getUser();
        }

        if(isset($user) && $user !== null) {
            $this->request->getSession()->getFlashBag()->add('info','Hello '.$user->getUsername().'. Nice to see you!');
        }
    }
}