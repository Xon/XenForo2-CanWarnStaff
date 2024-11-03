<?php
/**
 * @noinspection PhpMissingReturnTypeInspection
 */

namespace SV\CanWarnStaff\XF\Entity;

use XF\Phrase;

/**
 * @extends \XF\Entity\User
 */
class User extends XFCP_User
{
    /**
     * Prevent warning of users with prevent_warning permission
     *
     * @param Phrase|string|null $error
     * @return bool
     */
    public function canWarn(&$error = null)
    {
        if ($this->hasPermission('general', 'prevent_warning'))
        {
            return false;
        }

        return parent::canWarn($error);
    }

    /**
     * Permit warning of warnable admins/mods
     *
     * @return bool
     */
    public function isWarnable()
    {
        $ret = parent::isWarnable();
        if ($ret)
        {
            return true;
        }

        $visitor = \XF::visitor();

        if ($this->is_admin && $visitor->hasPermission('general', 'warn_admin'))
        {
            return true;
        }

        if ($this->is_moderator && $visitor->hasPermission('general', 'warn_mod'))
        {
            return true;
        }

        return false;
    }

    /**
     * Remove is_staff exclusion
     *
     * @param Phrase|string|null $error
     * @return bool
     */
    public function canBeReported(&$error = null)
    {
        $ret = parent::canBeReported($error);
        if ($ret)
        {
            return true;
        }

        if ($this->is_staff && \XF::visitor()->canReport($error))
        {
            return true;
        }

        return false;
    }
}
