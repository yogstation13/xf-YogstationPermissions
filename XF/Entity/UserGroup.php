<?php

namespace YogstationPermissions\XF\Entity;

class UserGroup extends XFCP_UserGroup {
    protected function setupApiResultData(
		\XF\Api\Result\EntityResult $result, $verbosity = self::VERBOSITY_NORMAL, array $options = [])
    {
        $result->title = $this->title;
        $result->display_priority = $this->display_style_priority;
    }
}