<?php

namespace Pickl\AppBundle\Service;

class PicklPercentage {

    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getPercentage($user)
    {
        $xpUser = $user->getExperience();
        $lvlUser = round(0.5*sqrt($xpUser/25.5)+1);

        $lvlEnd = $lvlUser;
        $xpEnd = $xpUser;
        while($lvlEnd == $lvlUser) {
            $xpEnd +=1;
            $lvlEnd = round(0.5*sqrt($xpEnd/25.5)+1);
        }

        $lvlStart = $lvlUser;
        $xpStart = $xpUser;
        while($lvlStart == $lvlUser) {
            $xpStart -=1;
            $lvlStart = round(0.5*sqrt($xpStart/25.5)+1);
        }

        $diffWithNext = $xpEnd - $xpUser;
        $diffWithPrevious = $xpUser - $xpStart;

        $total = $diffWithNext + $diffWithPrevious;
        // retourne le pourcentage avant le prochain niveau
        return (int)($diffWithPrevious/$total*100);
    }
}