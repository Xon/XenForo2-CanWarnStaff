<?php

namespace SV\CanWarnStaff\XF\Repository;

use SV\StandardLib\Helper;

/**
 * @extends \XF\Repository\User
 */
class User extends XFCP_User
{
    /**
     * Preload global permissions from permission_combination_id array
     *
     * @param array $permissionCombinationIds
     */
    public function preloadGlobalPermissionsFromIds(array $permissionCombinationIds)
    {
        Helper::perms()->cacheGlobalPermissions($permissionCombinationIds);
    }
}
