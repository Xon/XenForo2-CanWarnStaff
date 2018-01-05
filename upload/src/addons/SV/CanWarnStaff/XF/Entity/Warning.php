<?php

namespace SV\CanWarnStaff\XF\Entity;

class Warning extends XFCP_Warning {

	/**
	 * Add checks for: permission to manage staff warnings
	 * @param null $error
	 * @return bool
	 */
	public function canDelete(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
		if ($this->warning_user_id == $visitor->user_id)
		{
			return true;
		}

		return $visitor->hasPermission('general', 'manageWarning') && $this->checkManageStaffWarning();
	}

	/**
	 * Add checks for: permission to manage staff warnings
	 * @param null $error
	 * @return bool
	 */
	public function canEditExpiry(&$error = null)
	{
		$visitor = \XF::visitor();
		if (!$visitor->user_id)
		{
			return false;
		}
		if ($this->is_expired)
		{
			return false;
		}
		if ($this->warning_user_id == $visitor->user_id)
		{
			return true;
		}

		return $visitor->hasPermission('general', 'manageWarning') && $this->checkManageStaffWarning();
	}

	private function checkManageStaffWarning() {
		if ($this->User->is_admin) {
			return \XF::visitor()->hasPermission('general', 'manageWarning_admin');
		}

		if ($this->User->is_moderator) {
			return \XF::visitor()->hasPermission('general', 'manageWarning_mod');
		}

		return true;
	}
}
