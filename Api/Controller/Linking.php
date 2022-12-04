<?php

namespace YogstationPermissions\Api\Controller;

use XF\Api\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * @api-group Linking
 */
class Linking extends AbstractController
{
    /**
	 * @api-desc Generates a new linking key for a external account.
	 *
	 * @api-in <req> str $account_type The type of account (Byond or Discord)
	 * @api-in <req> str $account_id The external ID of the account being linked
	 * @api-in str $limit_ip The IP that should be considered to be making the request. If provided, this will be used to prevent brute force attempts.
	 *
	 * @api-out str $key the linking key generated.
     * @api-out str $url the url the end user needs to access to link.
	 */
    public function actionPost(ParameterBag $params)
	{
        $this->assertSuperUserKey();
		$this->assertApiScope('linking');

        $key = $this->service('YogstationPermissions:Linking\Generate', $params->account_type, $params->account_id)->generateKey();

        return $this->apiSuccess([
            'key' => $key,
            'url' => \XF::app()->router('public')->buildLink('canonical:linking/link', $key, ["key" => $key]),
		]);
    }

    public function actionGet(ParameterBag $params)
    {
        $this->assertSuperUserKey();
        $this->assertApiScope('linking');
        
        $linked_account_finder = \XF::finder('YogstationPermissions:LinkedAccount');
        $group_finder = \XF::finder('XF:UserGroup');

        $linked_account = $linked_account_finder->where('account_id', $params->account_id)
                       ->where('account_type', $params->account_type)
                       ->with('User')->fetchOne();

        if(!$linked_account)
        {
            return $this->error(\XF::phrase('yg_no_linked_account'));
        }

        $user = $linked_account->User;
        $group = $group_finder
            ->whereSql("FIND_IN_SET(group_id, $user->secondary_group_ids) AND group_id NOT IN (71, 66, 14)")
            ->order('display_style_priority', 'DESC')->fetchOne();

        return $this->apiSuccess([
            'user' => $user,
            'display_group' => $group,
        ]);
    }
}