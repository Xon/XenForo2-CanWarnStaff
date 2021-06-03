<?php
/**
 * @noinspection PhpMissingReturnTypeInspection
 */

namespace SV\CanWarnStaff\XF\Entity;

class ProfilePost extends XFCP_ProfilePost
{
    /**
     * @param \XF\Phrase|string|null $error
     * @return bool
     */
    public function canWarn(&$error = null)
    {
        if ($this->User && $this->User->hasPermission('profilePost', 'prevent_warning'))
        {
            return false;
        }

        return parent::canWarn($error);
    }
}
