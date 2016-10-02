<?php

namespace Pickl\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Pickl\AppBundle\Entity\Category;
use Pickl\AppBundle\Entity\Image;

class LoadCategory implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        // Liste des noms de catégorie à ajouter
        $categories = array(
            array(
                'name' => 'Art',
                'picture' => 'art.jpg'
            ),
            array(
                'name' => 'Comic',
                'picture' => 'comic.jpg'
            ),
            array(
                'name' => 'Craft',
                'picture' => 'craft.jpg'
            ),
            array(
                'name' => 'Dance',
                'picture' => 'dance.jpg'
            ),
            array(
                'name' => 'Design',
                'picture' => 'design.jpg'
            ),
            array(
                'name' => 'Fashion',
                'picture' => 'fashion.jpg'
            ),
            array(
                'name' => 'Movie',
                'picture' => 'movie.jpg'
            ),
            array(
                'name' => 'Food',
                'picture' => 'food.jpg'
            )
        );

        foreach ($categories as $tabCategory) {
            // On crée la catégorie
            $category = new Category();
            $category->setName($tabCategory['name']);

            $picture = new Image();
            $picture->setUrl($tabCategory['picture']);

            $category->setPicture($picture);

            // On la persiste
            $manager->persist($category);
        }

        // On déclenche l'enregistrement de toutes les catégories
        $manager->flush();
    }
}