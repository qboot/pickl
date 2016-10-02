<?php

namespace Pickl\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Pickl\AppBundle\Entity\Reward;
use Pickl\AppBundle\Entity\Image;

class LoadReward implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        // ATTENTION : ne pas changer l'ordre
        // Si non fonctionnel faire un drop base et update puis refixture
        // les ids doivent commencer précisément à 1 et correspondre aux règles du fichier de validation RewardsListener
        $rewards = array(
            array(
                'name' => 'First comment written',
                'description' => '',
                'icon' => '1st_comment.png'
            ),
            array(
                'name' => '25 comments written',
                'description' => '',
                'icon' => '25th_comment.png'
            ),
            array(
                'name' => '50 comments written',
                'description' => '',
                'icon' => '50th_comment.png'
            ),
            array(
                'name' => '100 comments written',
                'description' => '',
                'icon' => '100_comment.png'
            ),
            array(
                'name' => '1000 comments written',
                'description' => '',
                'icon' => '1000_comment.png'
            ),
            array(
                'name' => '5000 comments written',
                'description' => '',
                'icon' => '5000_comment.png'
            ),
            array(
                'name' => 'First project published',
                'description' => '',
                'icon' => '1_project.png'
            ),
            array(
                'name' => '3 projects published',
                'description' => '',
                'icon' => '3_project.png'
            ),
            array(
                'name' => '10 projects published',
                'description' => '',
                'icon' => '10_project.png'
            ),
            array(
                'name' => 'First project liked',
                'description' => '',
                'icon' => '1st_like.png'
            ),
            array(
                'name' => '5 projects liked',
                'description' => '',
                'icon' => '5th_like.png'
            ),
            array(
                'name' => '25 projects liked',
                'description' => '',
                'icon' => '25th_like.png'
            ),
            array(
                'name' => '50 projects liked',
                'description' => '',
                'icon' => '50_like.png'
            ),
            array(
                'name' => '100 projects liked',
                'description' => '',
                'icon' => '100_like.png'
            ),
            array(
                'name' => '1 dollar given',
                'description' => '',
                'icon' => '1_dollar.png'

            ),
            array(
                'name' => '25 dollars given',
                'description' => '',
                'icon' => '25_dollar.png'
            ),
            array(
                'name' => '50 dollars given',
                'description' => '',
                'icon' => '50_dollar.png'
            ),
            array(
                'name' => '100 dollars given',
                'description' => '',
                'icon' => '100_dollar.png'
            ),
            array(
                'name' => '1000 dollars given',
                'description' => '',
                'icon' => '1000_dollar.png'
            ),
            array(
                'name' => '2500 dollars given',
                'description' => '',
                'icon' => '2500_dollar.png'
            ),
            array(
                'name' => '7 days since registration',
                'description' => '',
                'icon' => '7_days.png'
            ),
            array(
                'name' => '1 month since registration',
                'description' => '',
                'icon' => '1_month.png'
            ),
            array(
                'name' => '3 months since registration',
                'description' => '',
                'icon' => '3_months.png'
            ),
            array(
                'name' => '1 year since registration',
                'description' => '',
                'icon' => '1_year.png'
            ),
            array(
                'name' => '3 years since registration',
                'description' => '',
                'icon' => '3_years.png'
            ),
            array(
                'name' => 'Subscribe to the newsletter',
                'description' => '',
                'icon' => 'subscribe_to_the_newsletter.png'
            ),
            array(
                'name' => 'Registration completed',
                'description' => '',
                'icon' => 'registration_completed.png'
            ),
        );

        foreach ($rewards as $tabReward) {
            $reward = new Reward();

            $reward->setName($tabReward['name']);
            $reward->setDescription($tabReward['description']);

            $icon = new Image();
            $icon->setUrl($tabReward['icon']);

            $reward->setIcon($icon);

            // On persiste
            $manager->persist($reward);
        }

        // On flush
        $manager->flush();
    }
}