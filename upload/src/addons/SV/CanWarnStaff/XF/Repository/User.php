<?php

namespace SV\CanWarnStaff\XF\Repository;

use SV\CanWarnStaff\PermissionCacheProtectedCracker;

class User extends XFCP_User {
	/**
	 * Preload global permissions from permission_combination_id array
	 * @param array $permissionCombinationIds
	 */
	public function preloadGlobalPermissionsFromIds(array $permissionCombinationIds) {

        $cachedPerms = PermissionCacheProtectedCracker::getCachedGlobalPerms();
        foreach($permissionCombinationIds as $key => $permissionCombinationId)
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

		$permissionCombinations = $finder->fetchColumns([
			'permission_combination_id',
			'cache_value'
		]);

		foreach ($permissionCombinations as $permissionCombination) {
			if ($permissionCombination['cache_value']) {
				$cache = @unserialize($permissionCombination['cache_value']);
				\XF::permissionCache()->setGlobalPerms(
					$permissionCombination['permission_combination_id'],
					$cache
				);
			}
		}
	}
}
