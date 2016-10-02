<?php

namespace Pickl\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Pickl\AppBundle\Entity\Project;
use Pickl\AppBundle\Entity\User;
use Pickl\AppBundle\Entity\Rank;
use Pickl\AppBundle\Entity\Category;
use Pickl\AppBundle\Entity\Contribution;
use Pickl\AppBundle\Entity\Counterpart;
use Pickl\AppBundle\Entity\Favorite;
use Pickl\AppBundle\Entity\Image;
use Pickl\AppBundle\Entity\Tag;
use Pickl\AppBundle\Entity\Comment;

class LoadProject implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        /*$rank = new Rank();
        $rank->setName('Test');
        $rank->setMinLevel('102');

        $icon = new Image();
        $icon->setUrl('http://super_recompense.fr');
        $icon->setUpdateAt(new \Datetime());

        $user = new User();
        $user->setUsername('user');
        $user->setEmail('user@local.host');
        $user->setPlainPassword('user');
        $user->setEnabled(true);
        $user->setRank($rank);

        $category = new Category();
        $category->setName('SpecialProjet');

        $words = array(
            'tagprojet1',
            'tagprojet2',
            'tagprojet3'
        );

        $image = new Image();
        $image->setUrl('http://google.fr');
        $image->setUpdateAt(new \Datetime());

        $project = new Project();
        $project->setTitle('Test');
        $project->setRequestedAmount('10000');
        $project->setCollectedAmount('5000');
        $project->setSmallDescription('Bonjour je suis un projet');
        $project->setBigDescription('Lorem ipsum dolor sit amet consectetur ipsum dol el mira');
        $project->setCountry('France');
        $project->setUser($user);
        $project->setCategory($category);
        $project->setPicture($image);
        $project->setDuration(60);

        foreach($words as $word)
        {
            $tag = new Tag();
            $tag->setWord($word);
            $project->addTag($tag);
        }

        $comment = new Comment();
        $comment->setProject($project);
        $comment->setUser($user);
        $comment->setMessage('Mon premier commentaire !!!');

        $amounts = array(
            20,
            150,
            12,
            1000,
        );

        foreach($amounts as $amount)
        {
            $contribution = new Contribution();
            $contribution->setUser($user);
            $contribution->setProject($project);
            $contribution->setAmount($amount);
            $manager->persist($contribution);
        }

        $counterpart = new Counterpart();
        $counterpart->setTitle('Sac à dos swag');
        $counterpart->setDescription('Raconter qu\'un petit sac à dos cest la hype et tout');
        $counterpart->setMinAmount(50);
        $counterpart->setProject($project);

        $favorite = new Favorite();
        $favorite->setUser($user);
        $favorite->setProject($project);

        $manager->persist($project);
        $manager->persist($comment);
        $manager->persist($counterpart);
        $manager->persist($favorite);

        // Projet 2
        $user = new User();
        $user->setUsername('user2');
        $user->setEmail('user2@local.host');
        $user->setPlainPassword('user2');
        $user->setEnabled(true);
        $user->setRank($rank);

        $image = new Image();
        $image->setUrl('3');
        $image->setUpdateAt(new \Datetime());

        $project = new Project();
        $project->setTitle('Projet2');
        $project->setRequestedAmount('2500');
        $project->setCollectedAmount('10');
        $project->setSmallDescription('Tour du monde');
        $project->setBigDescription('Bonjour je veux faire le tour du monde, qui pour maider ?');
        $project->setCountry('Nouvelle-Zélande');
        $project->setUser($user);
        $project->setPicture($image);
        $project->setCategory($category);
        $project->setDuration(60);

        $manager->persist($project);

        $amounts = array(
            10000,
            2,
            400
        );

        foreach($amounts as $amount)
        {
            $contribution = new Contribution();
            $contribution->setUser($user);
            $contribution->setProject($project);
            $contribution->setAmount($amount);
            $manager->persist($contribution);
        }

        // Projet 3
        $user = new User();
        $user->setUsername('user3');
        $user->setEmail('user3@local.host');
        $user->setPlainPassword('user3');
        $user->setEnabled(true);
        $user->setRank($rank);

        $image = new Image();
        $image->setUrl('43');
        $image->setUpdateAt(new \Datetime());

        $project = new Project();
        $project->setTitle('Projet3');
        $project->setRequestedAmount('750000');
        $project->setCollectedAmount('400000');
        $project->setSmallDescription('Toupie volante');
        $project->setBigDescription('Jai un super ambitieux pour vous en mettre plein les yeux, ça naboutira jamais mais je peux avoir de largent ? Merci');
        $project->setCountry('Tristesse');
        $project->setUser($user);
        $project->setCategory($category);
        $project->setPicture($image);
        $project->setDuration(60);

        $manager->persist($project);

        $amounts = array(
            5012,
            4,
            70,
            200,
            20
        );

        foreach($amounts as $amount)
        {
            $contribution = new Contribution();
            $contribution->setUser($user);
            $contribution->setProject($project);
            $contribution->setAmount($amount);
            $manager->persist($contribution);
        }

        // Projet 4
        $user = new User();
        $user->setUsername('user4');
        $user->setEmail('user4@local.host');
        $user->setPlainPassword('user4');
        $user->setEnabled(true);
        $user->setRank($rank);

        $image = new Image();
        $image->setUrl('65');
        $image->setUpdateAt(new \Datetime());

        $project = new Project();
        $project->setTitle('Projet4');
        $project->setRequestedAmount('1000');
        $project->setCollectedAmount('50');
        $project->setSmallDescription('Pour mon chat :(');
        $project->setBigDescription('Jai écrasé mon chat la semaine dernière en me garant, RIP. Aidez moi à oublier, merci...');
        $project->setCountry('ChatTown');
        $project->setUser($user);
        $project->setCategory($category);
        $project->setPicture($image);
        $project->setDuration(60);

        $manager->persist($project);

        $amounts = array(
            50
        );

        foreach($amounts as $amount)
        {
            $contribution = new Contribution();
            $contribution->setUser($user);
            $contribution->setProject($project);
            $contribution->setAmount($amount);
            $manager->persist($contribution);
        }


        // ON FLUSH TOUT
        $manager->flush();

        */
    }
}
