<?php

namespace YogstationPermissions\XF\Entity;

class User extends XFCP_User {

    /**
     * @return String[]
     */
    public function getPermissions() {

        $permissions = [];

        foreach ($this->getPermissionSet()->getGlobalPerms() as $group => $group_values)
        {
            foreach($group_values as $permission => $value)
            {
                if ($value == true)
                {
                    $permissions[] = $group . "." . $permission;
                }
                elseif ($value)
                {
                    $permissions[] = $group . "." . $permission . '.' . $value;
                }
            }
        }

        return $permissions;
    }

    protected function setupApiResultData(
		\XF\Api\Result\EntityResult $result, $verbosity = self::VERBOSITY_NORMAL, array $options = [])
    {
        parent::setupApiResultData($result, $verbosity, $options);

        $result->permissions = $this->getPermissions();

        $linked_accounts = [];

        foreach ($this->LinkedAccounts as $account)
        {
            $linked_accounts[$account->account_type] = $account->account_id;
        }

        $result->linked_accounts = count((array) $linked_accounts) == 0 ? null : $linked_accounts;
    }
}