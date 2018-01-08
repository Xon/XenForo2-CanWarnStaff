<?php

namespace SV\CanWarnStaff\XF\Entity;

class User extends XFCP_User
{
    /**
     * Prevent warning of users with prevent_warning permission
     *
     * @param null $error
     * @return bool
     */
    public function canWarn(&$error = null)
    {
        return (
            parent::canWarn($error) &&
            !$this->hasPermission('general', 'prevent_warning')
        );
    }

    /**
     * Permit warning of warnable admins/mods
     *
     * @return bool
     */
    public function isWarnable()
    {
        return (
            parent::isWarnable() ||
            ($this->is_admin && \XF::visitor()->hasPermission('general', 'warn_admin')) ||
            ($this->is_moderator && \XF::visitor()->hasPermission('general', 'warn_mod'))
        );
    }

    /**
     * Remove is_staff exclusion
     *
     * @param null $error
     * @return bool
     */
    public function canBeReported(&$error = null)
    {
        $parentResult = parent::canBeReported();

        if (\XF::visitor()->canReport($error) && $this->is_staff)
        {
            return true;
        }

        return $parentResult;
    }
}
