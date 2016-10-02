<?php

namespace Pickl\AppBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Pickl\AppBundle\Entity\Image;
use Pickl\AppBundle\Entity\Project;

class ProjectCreationListener
{
    public function prePersist(LifecycleEventArgs $args) {
        $entity = $args->getObject();

        // On ne cible que les projects
        if (!$entity instanceof Project) {
            return;
        }

        $project = $entity;

        $em = $args->getObjectManager();

        if($project->getPicture()->getFile() == null)
        {

            $newfile = 'defaultProject'.uniqid().'.png';

            copy(__DIR__.'../../../../../web/uploads/img/defaultProject.png', __DIR__.'../../../../../web/uploads/img/'.$newfile);

            $img = new Image();
            $img->setUrl($newfile);
            $project->setPicture($img);
        }
    }
}
