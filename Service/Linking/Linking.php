<?php

namespace YogstationPermissions\Service\Linking;

class Linking extends \XF\Service\AbstractService
{

    protected $user_id;
    protected $linking_key;

    public function __construct(\XF\App $app, $user_id, $linking_key)
	{
		parent::__construct($app);
        $this->user_id = $user_id;
        $this->linking_key = $linking_key;
    }
    
    public function link()
    {
        $this->removeOldAccounts();

        $linked_account = $this->em()->create('YogstationPermissions:LinkedAccount');
        $linked_account->user_id = $this->user_id;
        $linked_account->account_type = $this->linking_key->account_type;
        $linked_account->account_id = $this->linking_key->account_id;

        $linked_account->save();

        return $linked_account;
    }

    public function removeOldAccounts() {
        $finder = \XF::finder('YogstationPermissions:LinkedAccount');

        $linked_account = $finder->where('account_type', $this->linking_key->account_type)
                       ->whereOr(
                           ['account_id', $this->linking_key->account_type],
                           ['user_id', $this->user_id]
                        )->fetchOne();

        if($linked_account) {
            $linked_account->delete();
        }
    }
}