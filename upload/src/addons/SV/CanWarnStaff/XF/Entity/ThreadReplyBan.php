<?php

namespace SV\CanWarnStaff\XF\Entity;

use SV\CanWarnStaff\Globals;

/**
 * @extends \XF\Entity\ThreadReplyBan
 */
class ThreadReplyBan extends XFCP_ThreadReplyBan
{
    /**
     * Check for permission to delete thread reply bans
     */
    protected function _preDelete()
    {
        if (Globals::$permCheckInDelete ?? false)
        {
            $user = $this->User;
            if ($user !== null)
            {
                $visitor = \XF::visitor();
                if ($user->is_admin && !$visitor->hasPermission('general', 'manageWarning_admin'))
                {
                    $this->error(\XF::phrase('no_permission_to_delete_thread_reply_ban_from_admin'));
                }
                if ($user->is_moderator && !$visitor->hasPermission('general', 'manageWarning_mod'))
                {
                    $this->error(\XF::phrase('no_permission_to_delete_thread_reply_ban_from_mod'));
                }
            }
        }

        parent::_preDelete();
    }
}
