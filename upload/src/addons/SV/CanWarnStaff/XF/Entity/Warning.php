<?php
/**
 * @noinspection PhpMissingReturnTypeInspection
 */

namespace SV\CanWarnStaff\XF\Entity;

use XF\Phrase;

/**
 * @extends \XF\Entity\Warning
 */
class Warning extends XFCP_Warning
{

    /**
     * Add checks for: permission to manage staff warnings
     *
     * @param Phrase|string|null $error
     * @return bool
     */
    public function canDelete(&$error = null)
    {
        $ret = parent::canDelete($error);
        if (!$ret)
        {
            return false;
        }

        return $this->checkManageStaffWarning();
    }

    /**
     * Add checks for: permission to manage staff warnings
     *
     * @param Phrase|string|null $error
     * @return bool
     */
    public function canEditExpiry(&$error = null)
    {
        $ret = parent::canEditExpiry($error);
        if (!$ret)
        {
            return false;
        }

        return $this->checkManageStaffWarning();
    }

    /**
     * @return bool True if warning is for a normal user,
     *              and True if warning is for staff and visitor can manage warnings for them,
     *              otherwise False.
     */
    private function checkManageStaffWarning(): bool
    {
        $visitor = \XF::visitor();
        if (!$visitor->user_id)
        {
            return false;
        }

        if (!$this->User)
        {
            return false;
        }

        if ($this->User->is_admin)
        {
            return (bool)$visitor->hasPermission('general', 'manageWarning_admin');
        }

        if ($this->User->is_moderator)
        {
            return (bool)$visitor->hasPermission('general', 'manageWarning_mod');
        }

        return true;
    }
}
