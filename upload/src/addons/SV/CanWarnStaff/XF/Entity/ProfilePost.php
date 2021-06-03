<?php
/**
 * @noinspection PhpMissingReturnTypeInspection
 */

namespace SV\CanWarnStaff\XF\Entity;

class ProfilePost extends XFCP_ProfilePost
{
    /**
     * Prevent warning of profile posts in case of prevent_warning permission
     *
     * @param \XF\Phrase|string|null $error
     * @return bool
     */
    public function canWarn(&$error = null)
    {
        return (
            parent::canWarn($error) &&
            (!$this->User || !$this->User->PermissionSet->hasGlobalPermission('profilePost', 'prevent_warning'))
        );
    }

    public function getLatestComments()
    {
        // workaround XF2.04 (and earlier) bug.

        /** @var \XF\Repository\ProfilePost $profilePostRepo */
        $profilePostRepo = $this->repository('XF:ProfilePost');
        $profilePostRepo->addCommentsToProfilePosts([$this->profile_post_id => $this]);

        return $this->_getterCache['LatestComments'] ?? [];
    }
}
