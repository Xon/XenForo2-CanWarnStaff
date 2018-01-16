<?php

namespace SV\CanWarnStaff\XF\Entity;

class ThreadReplyBan extends XFCP_ThreadReplyBan
{
    /**
     * Check for permission to delete thread reply bans
     *
     * @return bool|void
     */
    protected function _preDelete()
    {
        if ($this->BannedBy && $this->BannedBy->is_admin && !\XF::visitor()->hasPermission('general', 'manageWarning_admin'))
        {
            $this->error(\XF::phrase('no_permission_to_delete_thread_reply_ban_from_admin'));
        }
        if ($this->BannedBy && $this->BannedBy->is_moderator && !\XF::visitor()->hasPermission('general', 'manageWarning_mod'))
        {
            $this->error(\XF::phrase('no_permission_to_delete_thread_reply_ban_from_mod'));
        }

        parent::_preDelete();
    }

}
