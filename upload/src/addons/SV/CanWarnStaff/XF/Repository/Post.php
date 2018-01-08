<?php

namespace SV\CanWarnStaff\XF\Repository;

class Post extends XFCP_Post {

	public function findPostsForThreadView(\XF\Entity\Thread $thread, array $limits = []) {
		/** @var \SV\CanWarnStaff\XF\Finder\Post $finder */
		$finder = parent::findPostsForThreadView($thread, $limits);

		/** @var \SV\CanWarnStaff\XF\Repository\User $userRepo */
		$userRepo = \XF::repository('XF:User');
		$userRepo->preloadGlobalPermissionsFromFinder($finder);

		return $finder;
	}

	public function findNewestPostsInThread(\XF\Entity\Thread $thread, $newerThan, array $limits = []) {
		$finder = parent::findNewestPostsInThread($thread, $newerThan, $limits);

		/** @var \SV\CanWarnStaff\XF\Repository\User $userRepo */
		$userRepo = \XF::repository('XF:User');
		$userRepo->preloadGlobalPermissionsFromFinder($finder);

		return $finder;
	}

	public function findNextPostsInThread(\XF\Entity\Thread $thread, $newerThan, array $limits = []) {
		$finder = parent::findNextPostsInThread($thread, $newerThan, $limits);

		/** @var \SV\CanWarnStaff\XF\Repository\User $userRepo */
		$userRepo = \XF::repository('XF:User');
		$userRepo->preloadGlobalPermissionsFromFinder($finder);

		return $finder;
	}


}
