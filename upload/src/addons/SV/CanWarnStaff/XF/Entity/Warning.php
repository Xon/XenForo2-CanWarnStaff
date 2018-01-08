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
		$parentResult = parent::canDelete();
		$visitor = \XF::visitor();

		if (!$visitor->user_id || !$this->checkManageStaffWarning()) {
			return false;
		}

		return $parentResult;
	}

	/**
	 * Add checks for: permission to manage staff warnings
	 * @param null $error
	 * @return bool
	 */
	public function canEditExpiry(&$error = null)
	{
		$parentResult = parent::canDelete();
		$visitor = \XF::visitor();

		if (!$visitor->user_id || !$this->checkManageStaffWarning()) {
			return false;
		}

		return $parentResult;
	}

	/**
	 * @return bool True if warning is for a normal user,
	 *              and True if warning is for staff and visitor can manage warnings for them,
	 *              otherwise False.
	 */
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
