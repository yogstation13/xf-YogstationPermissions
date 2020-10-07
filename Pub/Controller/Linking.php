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

        $linking_key = \XF::finder('YogstationPermissions:LinkingKey')
            ->where('linking_key', $params->key)
            ->fetchOne();

        if(!$linking_key) {
            return $this->error(\XF::phrase('yg_key_not_exist'));
        }

        $this->service('YogstationPermissions:Linking\Linking', $visitor->user_id, $linking_key)->link();

        return $this->redirect($this->buildLink('linking/success'));
    }

    public function actionSuccess() {
        return $this->view('YogstationPermissions:Linking\Success', 'yg_linking_success');
    }
}