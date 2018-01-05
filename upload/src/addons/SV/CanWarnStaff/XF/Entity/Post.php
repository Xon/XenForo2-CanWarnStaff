<?php

namespace SV\CanWarnStaff\XF\Entity;

class Post extends XFCP_Post {

	/**
	 * Prevent warning of posts in case of prevent_warning permission
	 * @param null $error
	 * @return bool
	 */
	public function canWarn(&$error = null) {
		return (
			parent::canWarn($error) &&
		    !$this->User->PermissionSet->hasGlobalPermission('forum', 'prevent_warning')
		);
	}

}
