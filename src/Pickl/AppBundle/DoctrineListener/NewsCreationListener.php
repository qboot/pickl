<?php

namespace Pickl\AppBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Pickl\AppBundle\Entity\Image;
use Pickl\AppBundle\Entity\News;

class NewsCreationListener
{
    public function prePersist(LifecycleEventArgs $args) {
        $entity = $args->getObject();

        // On ne cible que les projects
        if (!$entity instanceof News) {
            return;
        }

        $news = $entity;

        $em = $args->getObjectManager();

        if($news->getPicture()->getFile() == null)
        {
            $newfile = 'defaultNews'.uniqid().'.png';

            copy(__DIR__.'../../../../../web/uploads/img/defaultNews.png', __DIR__.'../../../../../web/uploads/img/'.$newfile);

            $img = new Image();
            $img->setUrl($newfile);
            $news->setPicture($img);
        }
    }
}
