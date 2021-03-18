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
	 * @api-desc Gets users for a group
	 *
	 * @api-in <req> str $groups Comma delimited group id's 
     * @api-in str $account_type optional account type, defaults to byond
	 *
	 */

    public function actionGet(ParameterBag $params)
    {
        $this->assertSuperUserKey();
        $this->assertApiScope('linking');

        $group_ids = $this->filter('groups', 'str');
        $account_type = $this->filter('account_type', 'str');
        $account_type = empty($account_type) ? 'byond' : $account_type;

        if (empty($group_ids)) {
            return $this->error(\XF::phrase('yg_atleast_one_group'));
        }

        $group_ids = explode(',', $this->filter('groups', 'str'));
        $group_priorities = [];
        $response = [];

        foreach($group_ids as $group_id) {
            $groupfinder = \XF::finder('XF:UserGroup');
            $userfinder = \XF::finder('XF:User')->isValidUser();
            
            $group = $groupfinder->where("user_group_id", $group_id)->fetchOne();
            if(!$group) {
                return $this->error(\XF::phrase('yg_invalid_group'));
            }
            $group_obj = [
                "user_group_id" => $group->user_group_id,
                "name" => $group->title,
                "priority" => $group->display_style_priority
            ];
        
            $query = $userfinder->whereOr(
                [$userfinder->expression('FIND_IN_SET(' . $userfinder->quote($group_id) . ", " . $userfinder->columnSqlName("secondary_group_ids") . ")")],
                ["user_group_id", $group_id]
            );
            $users = $query->fetch();
            $new_users = [];
            foreach($users as $user) {
                $username = "";
                foreach($user->LinkedAccounts as $link) {
                    if($link->account_type !== $account_type) continue;
                    $username = $link->account_id;
                }
                if(empty($username)) {
                    $username = $user->Profile->custom_fields->getFieldValue('Byond');
                }
                if(empty($username)) {
                    $username = $user->username;
                }
                $new_users[] = $username;
            }
            $group_obj["users"] = $new_users;
            if (\XF::$debugMode) {
                $group_obj["query"] = $query->getQuery();
            }
            $response[] = $group_obj;
        }
        
        usort($response, function ($item1, $item2) {
            return $item2["priority"] <=> $item1["priority"];
        });

        return $this->apiSuccess([
            "groups" => $response
        ]);



    }


}
