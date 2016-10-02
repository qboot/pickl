<?php

namespace Pickl\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Pickl\AppBundle\Entity\Newsletter;

class LoadNewsletter implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        $mails = array(
            'test@gmail.com',
            'hurryup@laposte.net',
            'admin@local.host',
            'jean-mi@gmail.com',
            'mimilapine@outlook.com'
        );

        foreach ($mails as $mail) {
            $newsletter = new Newsletter();
            $newsletter->setMail($mail);

            // On persiste
            $manager->persist($newsletter);
        }

        // On flush
        $manager->flush();
    }
}