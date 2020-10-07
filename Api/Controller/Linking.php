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

        $key = $this->service('YogstationPermissions:Linking\Generate', $params->account_type, $params->account_type)->generateKey();

        return $this->apiSuccess([
            'key' => $key,
            'url' => \XF::app()->router('public')->buildLink('canonical:linking/link', $key, ["key" => $key]),
		]);
    }

    public function actionGet(ParameterBag $params)
    {
        $this->assertSuperUserKey();
        $this->assertApiScope('linking');
        
        $finder = \XF::finder('YogstationPermissions:LinkedAccount');

        $user = $finder->where('account_id', $params->account_type)
                       ->where('account_type', $params->account_type)
                       ->with('User')->fetchOne();

        if(!$user)
        {
            return $this->error(\XF::phrase('yg_no_linked_account'));
        }

        return $this->apiSuccess([
            'user' => $user
        ]);
    }
}