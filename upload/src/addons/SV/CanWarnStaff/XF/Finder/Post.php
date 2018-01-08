<?php

namespace SV\CanWarnStaff\XF\Finder;

class Post extends XFCP_Post {
	public function withPermissionCombination()
	{
		$this->with(['User', 'User.PermissionCombination']);

		return $this;
	}
}