<?php

namespace SV\CanWarnStaff\XF\Entity;

class Post extends XFCP_Post
{
    /**
     * Prevent warning of posts in case of prevent_warning permission
     *
     * @param null $error
     * @return bool
     */
    public function canWarn(&$error = null)
    {
        if (
            $this->warning_id
            || !$this->user_id
            || !\XF::visitor()->user_id
            || $this->user_id == \XF::visitor()->user_id
        )
        {
            return false;
        }

        return (
            !$this->User->PermissionSet->hasGlobalPermission('forum', 'prevent_warning') &&
            parent::canWarn($error)
        );
    }
}
