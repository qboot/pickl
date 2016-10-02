<?php

namespace Pickl\AppBundle\Service;

class PicklOfTheDay {

    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /***
     * @return array
     * Retourne le projet du jour et les 3 projets "trends"
     * dans un tab associatif avec clef "ofTheDay" et "trends"
     */
    public function getProjectOfTheDayAndTrends() {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $projects = $em->getRepository('PicklAppBundle:Project')->findAll();

        $tabProjects = array();

        foreach($projects as $project) {
            $date = $project->getDailyProjectDate();
            if(isset($date) && !empty($date)) {
                if($date->format('Y/m/d') == (new \Datetime())->format('Y/m/d')) {
                    $amount = 0;
                    foreach($project->getContributions() as $contribution) {
                        if($contribution->getDate() > (new \Datetime())->modify('-24 hours'))
                            $amount += $contribution->getAmount();
                    }
                    // call ici (voir plus bas)
                    $ofTheDay = array(
                        'sum' => $amount,
                        'entity' => $project
                    );
                }
            }
        }

        foreach($projects as $project) {
            // si projet pas publié on passe au suivant
            if(!$project->getPublished())
                continue;

            $amount = 0;

            foreach($project->getContributions() as $contribution) {
                if($contribution->getDate() > (new \Datetime())->modify('-24 hours'))
                    $amount += $contribution->getAmount();
            }

            $tabProjects[] = array(
                'sum' => $amount,
                'entity' => $project
            );
        }

        usort($tabProjects, function($a, $b) {
            $ad = $a['sum'];
            $bd = $b['sum'];

            if ($ad == $bd) {
                return 0;
            }

            return $ad < $bd ? -1 : 1;
        });

        // tous les projets dans le bon sens
        $tabProjects = array_reverse($tabProjects);

        if(count($tabProjects)>3) {
            $trends = array(
                $tabProjects[1],
                $tabProjects[2],
                $tabProjects[3]
            );

            if(isset($ofTheDay)) {
                if($ofTheDay == $tabProjects[0]) {
                    $trends = array(
                        $tabProjects[1],
                        $tabProjects[2],
                        $tabProjects[3]
                    );
                } elseif($ofTheDay == $tabProjects[1]) {
                    $trends = array(
                        $tabProjects[0],
                        $tabProjects[2],
                        $tabProjects[3]
                    );
                } elseif($ofTheDay == $tabProjects[2]) {
                    $trends = array(
                        $tabProjects[0],
                        $tabProjects[1],
                        $tabProjects[3]
                    );
                } else {
                    $trends = array(
                        $tabProjects[0],
                        $tabProjects[1],
                        $tabProjects[2]
                    );
                }
            }
        } else {
            $trends = false;
        }

        // si un projet du jour est déjà défini pour ajd
        // call to $ofTheDay array dans le premier foreach
        if(isset($ofTheDay)) {
            // on fait le rendu
            // trends contient 3 arrays associatifs avec sum et entity (projects)
            // ofTheDay contient 1 array associatif avec sum et entity (project
            return array(
                'ofTheDay' => $ofTheDay,
                'trends' => $trends
            );
        }

        // on met le premier projet en tant que projet du jour pour l'instant
        $ofTheDay = $tabProjects[0];

        // on teste pour chaque projet s'il a déjà été projet du jour si oui on passe au suivant sinon on break et on l'ajoute
        foreach($tabProjects as $project)
        {
            $prjct = $project['entity'];

            if(!$prjct->getDailyProject())
            {
                // on l'ajoute
                // on break;
                $prjct->setDailyProject(true);
                $prjct->setDailyProjectDate(new \Datetime());
                $em->persist($prjct);
                $em->flush();
                $ofTheDay = $project;
                break;
            }
        }

        return array(
            'ofTheDay' => $ofTheDay,
            'trends' => $trends
        );
    }

}