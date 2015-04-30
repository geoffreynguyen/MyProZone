<?php

namespace EX\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class EXUserBundle extends Bundle
{
    public function getParent(){
        return 'FOSUserBundle';
    }
}
