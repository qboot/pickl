<?php

namespace Pickl\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Pickl\AppBundle\Entity\User;
use Pickl\AppBundle\Entity\Rank;

class LoadUser implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {

    }
}
