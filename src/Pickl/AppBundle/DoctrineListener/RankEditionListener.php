<?php

namespace Pickl\AppBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Pickl\AppBundle\Entity\Rank;

class RankEditionListener
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function postUpdate(LifecycleEventArgs $args) {
        $entity = $args->getObject();

        // On ne cible que les ranks
        if (!$entity instanceof Rank) {
            return;
        }

        $rank = $entity;

        $em = $args->getObjectManager();

        $users = $em->getRepository('PicklAppBundle:User')->findAll();

        foreach($users as $user) {
            $newUser = $this->container->get('pickl_app.doctrine_listener.user_creation')->hydrateUserRank($user, $em);
            $em->persist($newUser);
        }

        $em->flush();
    }
}
