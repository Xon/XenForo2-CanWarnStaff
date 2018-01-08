<?php

namespace SV\CanWarnStaff\XF\Repository;

class ProfilePost extends XFCP_ProfilePost {

	/**
	 * Preload global permission_combination from profile posts & comments
	 * @param \XF\Entity\ProfilePost[] $profilePosts
	 * @return \XF\Mvc\Entity\ArrayCollection
	 */
	public function addCommentsToProfilePosts($profilePosts) {
		/** @var \XF\Mvc\Entity\ArrayCollection $parentResult */
		$parentResult = parent::addCommentsToProfilePosts($profilePosts);

		$permCombIds = [];
		foreach ($profilePosts as $profilePostId => $profilePost) {
			$permCombIds[] = $profilePost->getRelation('User')->getValue('permission_combination_id');

			$comments = $profilePost->LatestComments;
			foreach ($comments as $comment) {
				$permCombIds[] = $comment->getRelation('User')->getValue('permission_combination_id');
			}
		}
		$uniquePermCombIds = array_unique($permCombIds);
		/** @var \SV\CanWarnStaff\XF\Repository\User $userRepo */
		$userRepo = \XF::repository('XF:User');
		$userRepo->preloadGlobalPermissionsFromIds($uniquePermCombIds);

		return $parentResult;
	}

}