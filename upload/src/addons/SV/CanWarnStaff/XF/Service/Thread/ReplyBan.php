<?php

namespace SV\CanWarnStaff\XF\Service\Thread;

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

        if (
            $this->user->is_staff &&
            (
                ($this->user->is_admin && \XF::visitor()->hasPermission('general', 'warn_admin')) ||
                ($this->user->is_moderator && \XF::visitor()->hasPermission('general', 'warn_mod'))
            )
        )
        {
            unset($errors['is_staff']);
        }

        return $errors;
    }
}
