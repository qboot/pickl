<?php

namespace Pickl\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Pickl\AppBundle\Entity\Tag;

class LoadTag implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        $words = array(
            'drone',
            'food',
            'video games',
            'cinema',
            'wears',
            'art',
            'paint',
            'relation',
            'humanity',
            'animal',
            'technology',
            'africa',
            'web',
            'services',
            'SMES',
            'crafting',
            'dance',
            'gastronomy',
            'luxury',
            'innovation',
            'sharing',
            'travel',
            'newspapers',
            'children',
            'sport',
            'youtube',
            'car',
            'people',
            'coffee'
        );

        foreach ($words as $word) {
            $tag = new Tag();
            $tag->setWord($word);

            // On persiste
            $manager->persist($tag);
        }

        // On flush
        $manager->flush();
    }
}