<?php
/**
 * @noinspection PhpMissingReturnTypeInspection
 */

namespace SV\CanWarnStaff\XF\Repository;

use SV\CanWarnStaff\XF\Repository\User as ExtendedUserRepo;
use SV\StandardLib\Helper;
use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Entity;
use XF\Repository\User as UserRepo;
use function array_unique;
use function count;

/**
 * @extends \XF\Repository\ProfilePost
 */
class ProfilePost extends XFCP_ProfilePost
{
    protected $recursionGuard = false;

    /**
     * Preload global permission_combination from profile posts & comments
     *
     * @param AbstractCollection|\XF\Entity\ProfilePost[] $profilePosts
     * @param bool                     $skipUnfurlRecrawl
     * @return AbstractCollection
     */
    public function addCommentsToProfilePosts($profilePosts, $skipUnfurlRecrawl = false)
    {
        /** @var AbstractCollection $parentResult */
        $parentResult = parent::addCommentsToProfilePosts($profilePosts, $skipUnfurlRecrawl);

        if ($this->recursionGuard || !\XF::visitor()->hasPermission('profilePost', 'warn'))
        {
            return $parentResult;
        }
        $oldRecursionGuard = $this->recursionGuard;
        $this->recursionGuard = true;
        try
        {
            $permCombIds = [];
            foreach ($profilePosts as $profilePost)
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
            if (count($permCombIds) !== 0)
            {
                $uniquePermCombIds = array_unique($permCombIds);
                /** @var ExtendedUserRepo $userRepo */
                $userRepo = Helper::repository(UserRepo::class);
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
