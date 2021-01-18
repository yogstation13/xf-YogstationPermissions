<?php

namespace YogstationPermissions\Api\Controller;

use XF\Api\Controller\AbstractController;
use XF\Mvc\ParameterBag;

/**
 * @api-group GroupUsers
 */
class GroupUsers extends AbstractController
{
    /**
	 * @api-desc Generates a new linking key for a external account.
	 *
	 * @api-in <req> str $groups Comma delimited group id's 
     * @api-in str $account_type Type of linked account to find, use account type "forums" for forums usernames. Defaults to "byond".
	 * @api-in bool $skip_missing If enabled, skips accounts without a linked account otherwise use forums username instead. Defaults to true.
     * @api-in bool $verbose Return full user object, if enabled ignores account_type parameter. Defaults to false.
	 *
	 * @api-out str $key the linking key generated.
     * @api-out str $url the url the end user needs to access to link.
	 */

    public function actionGet(ParameterBag $params)
    {
        $this->assertSuperUserKey();
        $this->assertApiScope('linking');

        $account_type = $this->filter('account_type', 'str');
        $account_type = empty($account_type) ? 'byond' : $account_type;
        
        $skip_missing = $this->filter('skip_missing', 'bool');
        $verbose = $this->filter('verbose', 'bool');
        
        $finder = \XF::finder('XF:User');
        $secondary_group_column = $finder->columnSqlName('secondary_group_ids');

        $group_ids = $this->filter('groups', 'str');

        if (empty($group_ids)) {
            return $this->error(\XF::phrase('yg_atleast_one_group'));
        }

        $group_ids = explode(',', $this->filter('groups', 'str'));

        $whereOr = [
            ['user_group_id', '=', $group_ids]
        ];

        foreach ($group_ids as $group) {
            $whereOr[] = $finder->expression('FIND_IN_SET(' . $finder->quote($group) . ', ' . $secondary_group_column . ')');
        }

        $users = $finder->whereOr($whereOr)->fetch();

        if(!$verbose) {
            $new_users = [];

            foreach ($users as $user) {
                $found = false;

                foreach ($user->LinkedAccounts as $linked_account) {
                    if($linked_account->account_type == $account_type) {
                        $new_users[] = $linked_account->account_id;
                        $found = true;
                    } 
                }

                if (!$found && !$skip_missing) {
                    $new_users[] = $user->username;
                }
            }

            $users = $new_users;
        }

        return $this->apiSuccess(["users" => $users, "query" => $finder->getQuery()]);
    }


}