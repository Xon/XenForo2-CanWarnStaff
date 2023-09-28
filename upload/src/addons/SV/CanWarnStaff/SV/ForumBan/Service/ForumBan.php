<?php

namespace SV\CanWarnStaff\SV\ForumBan\Service;

/**
 * Extends \SV\ForumBan\Service\ForumBan
 */
class ForumBan extends XFCP_ForumBan
{
    /**
     * Allow visitors with warn_admin or warn_mod permission to reply ban the respective groups
     *
     * @return array
     * @noinspection PhpMissingReturnTypeInspection
     */
    protected function _validate()
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