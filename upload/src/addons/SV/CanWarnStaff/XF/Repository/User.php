<?php

namespace SV\CanWarnStaff\XF\Repository;

use SV\Utils\BypassAccessStatus;

class User extends XFCP_User
{
    /**
     * Preload global permissions from permission_combination_id array
     *
     * @param array $permissionCombinationIds
     */
    public function preloadGlobalPermissionsFromIds(array $permissionCombinationIds)
    {
        $bypassAccessStatus = new BypassAccessStatus();
        $getter = $bypassAccessStatus->getPrivate(\XF::permissionCache(), 'globalPerms');
        $cachedPerms = $getter();

        foreach ($permissionCombinationIds as $key => $permissionCombinationId)
        {
            if (isset($cachedPerms[$permissionCombinationId]))
            {
                unset($permissionCombinationIds[$key]);
            }
        }

        if (!$permissionCombinationIds)
        {
            return;
        }

        $finder = $this->finder('XF:PermissionCombination')
                       ->where('permission_combination_id', $permissionCombinationIds);


        $permissionCombinations = $finder->fetchColumns(
            [
                'permission_combination_id',
                'cache_value'
            ]
        );

        foreach ($permissionCombinations as $permissionCombination)
        {
            if ($permissionCombination['cache_value'])
            {
                \XF\Entity\PermissionCombination::instantiateProxied($permissionCombination);
            }
        }
    }
}
