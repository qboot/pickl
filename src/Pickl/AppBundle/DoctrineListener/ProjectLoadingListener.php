<?php

namespace Pickl\AppBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Pickl\AppBundle\Entity\Project;

class ProjectLoadingListener
{
        public function postLoad(LifecycleEventArgs $args) {
            $entity = $args->getObject();

        // On ne cible que les projects
        if (!$entity instanceof Project) {
            return;
        }

        $project = $entity;
        $em = $args->getObjectManager();

        if($project->getPublished()) {
            if (new \Datetime() > $project->getEndingDate()) {
                $project->setFinished(true);
                $em->persist($project);
                $em->flush();
            }
        }
    }
}
