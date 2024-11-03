<?php
/**
 * @noinspection PhpMissingReturnTypeInspection
 */

namespace SV\CanWarnStaff\XF\Service\Thread;

/**
 * @extends \XF\Service\Thread\ReplyBan
 */
class ReplyBan extends XFCP_ReplyBan
{
    /**
     * Allow visitors with warn_admin or warn_mod permission to reply ban the respective groups
     *
     * @return array
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
