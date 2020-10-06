<?php

namespace YogstationPermissions\XF\Entity;

class User extends XFCP_User {

    /**
     * @return String[]
     */
    public function getPermissions() {

        $permissions = [];

        foreach ($this->getPermissionSet()->getGlobalPerms() as $group => $group_values) {
            foreach($group_values as $permission => $value) {
                if ($value == true) {
                    $permissions[] = $group . "." . $permission;
                } elseif ($value) {
                    $permissions[] = $group . "." . $permission . '.' . $value;
                }
            }
        }

        return $permissions;
    }

    protected function setupApiResultData(
		\XF\Api\Result\EntityResult $result, $verbosity = self::VERBOSITY_NORMAL, array $options = []
	) {
        parent::setupApiResultData($result, $verbosity, $options);

        $result->permissions = $this->getPermissions();
    }

}