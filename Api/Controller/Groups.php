<?php

namespace YogstationPermissions\Api\Controller;

use XF\Api\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * @api-group Groups
 */

class Groups extends AbstractController
{
    /**
	 * @api-desc Get list of user groups sorted by display rank.
	 *
	 *
	 * @api-out str $key the linking key generated.
     * @api-out str $url the url the end user needs to access to link.
	 */
    public function actionGet(ParameterBag $params)
	{
        $this->assertSuperUserKey();

        $groups = $this->finder('XF:UserGroup')->order('display_style_priority')->fetch();

        return $this->apiSuccess([
            'groups' => $groups
		]);
    }

}