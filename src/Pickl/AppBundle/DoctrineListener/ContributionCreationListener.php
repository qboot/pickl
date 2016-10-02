<?php

namespace Pickl\AppBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Pickl\AppBundle\Entity\Contribution;

class ContributionCreationListener
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }


    public function postPersist(LifecycleEventArgs $args) {
        $entity = $args->getObject();

        // On ne cible que les contributions
        if (!$entity instanceof Contribution) {
            return;
        }

        $contribution = $entity;

        $em = $args->getObjectManager();

        $project = $contribution->getProject();
        $project->setCollectedAmount($project->getCollectedAmount() + $contribution->getAmount());

        $token = $this->container->get('security.token_storage')->getToken();

        if($token === null)
            return;

        $user = $token->getUser();
        $user->setExperience($user->getExperience()+($contribution->getAmount()*100));

        $em->persist($user);
        $em->persist($project);
        $em->flush();
    }
}
