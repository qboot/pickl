<?php

namespace Pickl\AppBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Pickl\AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Pickl\AppBundle\Entity\Image;

class UserCreationListener
{
    public function hydrateUserRank(User $user, EntityManager $em) {

        // formule magique
        $user->setLevel(round(0.5*sqrt($user->getExperience()/25.5)+1));

        $rankRepository = $em->getRepository('PicklAppBundle:Rank');

        $ranks = $rankRepository->findAllOrderByLevelMin();

        // mettre le bon rank au user
        // récupérer tous les ranks
        foreach($ranks as $rank) {
            // si le level de l'utilisateur dépasse ou égal le rang minimum
            // on ajoute le rang à l'utilisateur
            // sinon on return
            if($user->getLevel() >= $rank->getMinLevel()) {
                $user->setRank($rank);
            } else {
                break;
            }
        }

        return $user;
    }

    public function preUpdate(LifecycleEventArgs $args) {
        $entity = $args->getObject();

        // On ne cible que les user
        if (!$entity instanceof User) {
            return;
        }

        $user = $entity;

        $em = $args->getObjectManager();

        $this->hydrateUserRank($user,$em);
    }

    public function prePersist(LifecycleEventArgs $args) {
        $entity = $args->getObject();

        // On ne cible que les user
        if (!$entity instanceof User) {
            return;
        }

        $user = $entity;

        $em = $args->getObjectManager();

        $this->hydrateUserRank($user,$em);

        $user->setRegistrationDate(new \Datetime());

        $newfile = 'defaultProfile'.uniqid().'.png';

        copy(__DIR__.'../../../../../web/uploads/img/defaultProfile.png', __DIR__.'../../../../../web/uploads/img/'.$newfile);

        $img = new Image();
        $img->setUrl($newfile);
        $user->setProfilePicture($img);

        $newfileBis = 'defaultCover'.uniqid().'.png';

        copy(__DIR__.'../../../../../web/uploads/img/defaultCover.png', __DIR__.'../../../../../web/uploads/img/'.$newfileBis);

        $imgBis = new Image();
        $imgBis->setUrl($newfileBis);
        $user->setCoverPicture($imgBis);

    }
}
