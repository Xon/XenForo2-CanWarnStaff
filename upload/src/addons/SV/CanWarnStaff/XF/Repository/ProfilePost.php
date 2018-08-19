<?php

namespace SV\CanWarnStaff\XF\Repository;

use XF\Mvc\Entity\Entity;

class ProfilePost extends XFCP_ProfilePost
{

    protected $recursionGuard = false;
    /**
     * Preload global permission_combination from profile posts & comments
     *
     * @param \XF\Entity\ProfilePost[] $profilePosts
     * @return \XF\Mvc\Entity\ArrayCollection
     */
    public function addCommentsToProfilePosts($profilePosts)
    {
        /** @var \XF\Mvc\Entity\ArrayCollection $parentResult */
        $parentResult = parent::addCommentsToProfilePosts($profilePosts);

        if ($this->recursionGuard || !\XF::visitor()->hasPermission('profilePost', 'warn'))
        {
            return $parentResult;
        }
        $oldRecursionGuard = $this->recursionGuard;
        $this->recursionGuard = true;
        try
        {
            $visitor = \XF::visitor();
            $permCombIds = [];
            foreach ($profilePosts as $profilePostId => $profilePost)
            {
                /** @var Entity $item */
                $user = $profilePost->getRelation('User');
                if ($user)
                {
                    $permCombIds[] = $user->getValue('permission_combination_id');
                }

                if (empty($profilePost->latest_comment_ids))
                {
                    continue;
                }

                $comments = $profilePost->LatestComments;
                foreach ($comments as $comment)
                {
                    /** @var Entity $comment */
                    $user = $comment->getRelation('User');
                    if ($user)
                    {
                        $permCombIds[] = $user->getValue('permission_combination_id');
                    }
                }
            }
            $uniquePermCombIds = array_unique($permCombIds);
            if ($uniquePermCombIds)
            {
                /** @var \SV\CanWarnStaff\XF\Repository\User $userRepo */
                $userRepo = \XF::repository('XF:User');
                $userRepo->preloadGlobalPermissionsFromIds($uniquePermCombIds);
            }
        }
        finally
        {
            $this->recursionGuard = $oldRecursionGuard;
        }
        return $parentResult;
    }
}
