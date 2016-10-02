<?php

namespace Pickl\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Pickl\AppBundle\Entity\Rank;
use Pickl\AppBundle\Entity\User;

class LoadRank implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        $ranks = array(
            '1' => 'Noobie Pickl',
            '2' => 'Baby Pickl',
            '3' => 'Small Pickl',
            '4' => 'Student Pickl',
            '5' => 'Initiate Pickl',
            '6' => 'Friendly Pickl',
            '7' => 'Generous Pickl',
            '8' => 'Hot Pickl',
            '9' => 'Incredible Pickl',
            '10' => 'Addict Pickl',
            '15' => 'Famous Pickl',
            '30' => 'Contributor Pickl',
            '40' => 'Investor Pickl',
            '50' => 'Expert Pickl',
            '60' => 'Veteran Pickl',
            '70' => 'Platinum Pickl',
            '80' => 'Elite Pickl',
            '90' => 'Master Pickl',
            '100' => 'God Pickl'
        );

        foreach ($ranks as $rankMinLevel => $rankName) {
            $rank = new Rank();
            $rank->setMinLevel($rankMinLevel);
            $rank->setName($rankName);

            // On persiste
            $manager->persist($rank);

            $rankAdmin = new Rank();
            $rankAdmin->setMinLevel($rankMinLevel);
            $rankAdmin->setName($rankName);
        }

        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@local.host');
        $user->setPlainPassword('admin');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_SUPER_ADMIN'));
        $user->setRank($rankAdmin);
        $manager->persist($user);
        // On flush
        $manager->flush();
    }
}