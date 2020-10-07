<?php

namespace YogstationPermissions\Entity;

use XF\Mvc\Entity\Entity;
use XF\Mvc\Entity\Structure;

class LinkedAccount extends Entity
{
    public static function getStructure(Structure $structure)
	{
        $structure->table = 'yg_linked_account';
		$structure->shortName = 'YG:LinkedAccount';
        $structure->primaryKey = 'user_id';
        $structure->columns = [
            'user_id' => ['type' => self::UINT],
            'account_type' => ['type' => self::STR, 'maxLength' => 50],
            'account_id' => ['type' => self::STR, 'maxLength' => 255]
        ];
        $structure->relations = [
			'User' => [
				'entity' => 'XF:User',
				'type' => self::TO_ONE,
				'conditions' => 'user_id',
				'primary' => true
            ]
        ];

        return $structure;
    }
}