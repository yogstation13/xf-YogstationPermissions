<?php

namespace YogstationPermissions\Pub\Controller;

use XF\Pub\Controller\AbstractController;
use XF\Mvc\ParameterBag;

class Linking extends AbstractController {
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertRegistrationRequired();
	}

    public function actionLink(ParameterBag $params) {
        $visitor = \XF::visitor();
        
        $linking_key = $this->filter('key', 'str');

        $linking_key = \XF::finder('YogstationPermissions:LinkingKey')
            ->where('linking_key', $linking_key)
            ->fetchOne();

        if(!$linking_key) {
            return $this->error(\XF::phrase('yg_key_not_exist'));
        }

        $this->service('YogstationPermissions:Linking\Linking', $visitor->user_id, $linking_key)->link();

        return $this->redirect($this->buildLink('linking/success', null, ["account_type" => $linking_key->account_type]));
    }

    public function actionSuccess() {
        return $this->view('YogstationPermissions:Linking\Success', 'yg_linking_success', ['account_type' => $this->filter('account_type', 'str')]);
    }
}