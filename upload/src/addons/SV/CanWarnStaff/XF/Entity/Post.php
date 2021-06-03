<?php
/**
 * @noinspection PhpMissingReturnTypeInspection
 */

namespace SV\CanWarnStaff\XF\Entity;

class Post extends XFCP_Post
{
    /**
     * Prevent warning of posts in case of prevent_warning permission
     *
     * @param \XF\Phrase|string|null $error
     * @return bool
     */
    public function canWarn(&$error = null)
    {
        if (
            $this->warning_id
            || !$this->user_id
            || !\XF::visitor()->user_id
            || $this->user_id === \XF::visitor()->user_id
        )
        {
            return false;
        }

        return (
            parent::canWarn($error) &&
            (!$this->User || !$this->User->PermissionSet->hasGlobalPermission('forum', 'prevent_warning'))
        );
    }
}
