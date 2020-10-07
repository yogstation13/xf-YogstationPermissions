<?php

namespace YogstationPermissions\Service\Linking;

class Generate extends \XF\Service\AbstractService
{

    protected $account_type;
    protected $account_id;

    public function __construct(\XF\App $app, $account_type, $account_id)
	{
		parent::__construct($app);
        $this->account_type = $account_type;
        $this->account_id = $account_id;
    }
    
    public function generateKey()
    {
        $key = \XF::generateRandomString(255);

        $linking_key = $this->em()->create('YogstationPermissions:LinkingKey');
        $linking_key->linking_key = $key;
        $linking_key->account_type = $this->account_type;
        $linking_key->account_id = $this->account_id;
        $linking_key->expires = \XF::$time + 60 * 15;

        $linking_key->save();

        return $key;
    }
}