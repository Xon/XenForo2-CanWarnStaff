<?php

namespace SV\CanWarnStaff\XF\Repository;

class User extends XFCP_User {

	/**
	 * Preload global permissions from post finder
	 * @param \SV\CanWarnStaff\XF\Finder\Post $finder Finder containing User.PermissionCombination relation
	 */
	public function preloadGlobalPermissionsFromFinder($finder) {
		$permissionCombinations = $finder->withPermissionCombination()->fetchColumns([
			'User.PermissionCombination.permission_combination_id',
			'User.PermissionCombination.cache_value'
		]);
		$uniqueIds = array_unique(array_column($permissionCombinations, 'permission_combination_id'));
		array_map(function ($permComb) use ($uniqueIds) {
			if (in_array($permComb['permission_combination_id'], $uniqueIds) && $permComb['cache_value']) {
				$cache = @unserialize($permComb['cache_value']);
				\XF::permissionCache()->setGlobalPerms($permComb['permission_combination_id'], $cache);
			}
		}, $permissionCombinations);
	}

	/**
	 * Preload global permissions from permission_combination_id array
	 * @param array $permissionCombinationIds
	 */
	public function preloadGlobalPermissionsFromIds(array $permissionCombinationIds) {
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