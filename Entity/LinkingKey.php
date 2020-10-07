<?php

namespace YogstationPermissions\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class LinkingKey extends Entity
{
    public static function getStructure(Structure $structure)
	{
        $structure->table = 'yg_linking_key';
		$structure->shortName = 'YG:LinkingKey';
        $structure->primaryKey = 'linking_key';
        $structure->columns = [
            'linking_key' => ['type' => self::STR, 'maxLength' => 255],
            'account_type' => ['type' => self::STR, 'maxLength' => 50],
            'account_id' => ['type' => self::STR, 'maxLength' => 255],
            'expires' => ['type' => self::UINT]
        ];

        return $structure;
    }
}