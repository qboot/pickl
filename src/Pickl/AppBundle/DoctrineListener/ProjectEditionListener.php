<?php

namespace Pickl\AppBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Pickl\AppBundle\Entity\Project;

class ProjectEditionListener
{
   public function preUpdate(LifecycleEventArgs $args) {
        $entity = $args->getObject();

        // On ne cible que les projects
        if (!$entity instanceof Project) {
            return;
        }

        $project = $entity;

        $em = $args->getObjectManager();

        if(!$project->getPublished()) {
            $project->setStartingDate(new \Datetime());
            $project->setEndingDate(new \Datetime());
            $project->hydrateEndingDate();
        }
    }
}
