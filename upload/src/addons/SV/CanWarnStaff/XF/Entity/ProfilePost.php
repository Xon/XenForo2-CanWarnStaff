<?php

namespace SV\CanWarnStaff\XF\Entity;

class ProfilePost extends XFCP_ProfilePost {

	/**
	 * Prevent warning of profile posts in case of prevent_warning permission
	 * @param null $error
	 * @return bool
	 */
	public function canWarn(&$error = null) {
		return (
			parent::canWarn($error) &&
			!$this->User->PermissionSet->hasGlobalPermission('profilePost', 'prevent_warning')
		);
	}

}
