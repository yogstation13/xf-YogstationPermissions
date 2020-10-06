<?php

namespace YogstationPermissions\XF\Entity;

class User extends XFCP_User {

    /**
     * @return String[]
     */
    public function getPermissions() {
        return ["this", "is", "a", "test"];
    }

    protected function setupApiResultData(
		\XF\Api\Result\EntityResult $result, $verbosity = self::VERBOSITY_NORMAL, array $options = []
	) {
        parent::setupApiResultData($result, $verbosity, $options);

        $result->permissions = $this->getPermissions();
    }

}