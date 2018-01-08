<?php


namespace SV\CanWarnStaff;

class PermissionCacheProtectedCracker extends \XF\PermissionCache
{
    public static function getCachedGlobalPerms()
    {
        $permCache = \XF::permissionCache();
        return $permCache->globalPerms;
    }
}
