<?php

namespace SV\CanWarnStaff\SV\ForumBan\Service;

/**
 * @extends \SV\ForumBan\Service\ForumBan
 */
class ForumBan extends XFCP_ForumBan
{
    protected function _validate(): array
    {
        $errors = parent::_validate();

        $user = $this->user;
        if ($user->is_staff &&
            (
                (!$user->is_admin && !$user->is_moderator) ||
                ($user->is_admin && \XF::visitor()->hasPermission('general', 'warn_admin')) ||
                ($user->is_moderator && \XF::visitor()->hasPermission('general', 'warn_mod'))
            )
        )
        {
            unset($errors['is_staff']);
        }

        return $errors;
    }
}