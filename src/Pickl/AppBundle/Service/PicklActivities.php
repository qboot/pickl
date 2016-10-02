<?php

namespace Pickl\AppBundle\Service;

class PicklActivities {

    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function findActivities($user, $nb = null)
    {
        $activities = array();

        foreach($user->getContributions() as $contribution)
        {
            $activities[] = array('date' => $contribution->getDate(),'activity' => $contribution);
        }

        foreach($user->getFavorites() as $like)
        {
            $activities[] = array('date' => $like->getDate(),'activity' => $like);
        }

        foreach($user->getComments() as $comment)
        {
            $activities[] = array('date' => $comment->getDate(),'activity' => $comment);
        }

        foreach($user->getProjects() as $project)
        {
            $activities[] = array('date' => $project->getStartingDate(),'activity' => $project);
        }

        $dates = array();
        foreach ($activities as $activity) {
            $dates[] = $activity['date'];
        }

        usort($activities, function($a, $b) {
            $ad = $a['date'];
            $bd = $b['date'];

            if ($ad == $bd) {
                return 0;
            }

            return $ad < $bd ? -1 : 1;
        });

        $activities = array_reverse($activities);

        if($nb === null)
            $nb = $this->container->getParameter('nb_activities_for_page_account');

        $activitiesList = array();

        for($i=0;$i<$nb;$i++)
        {
            if(isset($activities[$i]))
                $activitiesList[] = $activities[$i]['activity'];
        }

        $activitiesFinal = array();

        foreach($activitiesList as $activity)
        {
            $function = new \ReflectionClass(get_class($activity));

            $activitiesFinal[] = array(
                'class' => $function->getShortName(),
                'activity' => $activity)
            ;
        }

        return $activitiesFinal;
    }
}