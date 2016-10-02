<?php

namespace Pickl\AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PicklAppBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
