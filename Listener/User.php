<?php

namespace YogstationPermissions\Listener;

use XF\Mvc\Entity\Entity;

class User
{
    public static function userEntityStructure(\XF\Mvc\Entity\Manager $em, \XF\Mvc\Entity\Structure &$structure)
    {
            $structure->getters['permissions'] = true;
    }
}