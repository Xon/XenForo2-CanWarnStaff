<?php

namespace SV\CanWarnStaff\XF\Service\Thread;

class ReplyBan extends XFCP_ReplyBan {

	/**
	 * Allow visitors with warn_admin or warn_mod permission to reply ban the respective groups
	 * @return array
	 */
	protected function _validate()
	{
		$this->finalSetup();

		$this->replyBan->preSave();
		$errors = $this->replyBan->getErrors();

		if (
			$this->user->is_staff &&
			!($this->user->is_admin && \XF::visitor()->hasPermission('general', 'warn_admin')) &&
		    !($this->user->is_moderator && \XF::visitor()->hasPermission('general', 'warn_mod'))
		)
		{

			$errors['is_staff'] = \XF::phrase('staff_members_cannot_be_reply_banned');
		}

		return $errors;
	}

}
