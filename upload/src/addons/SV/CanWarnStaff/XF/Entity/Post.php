<?php
/**
 * @noinspection PhpMissingReturnTypeInspection
 */

namespace SV\CanWarnStaff\XF\Entity;

use XF\Phrase;

class Post extends XFCP_Post
{
    /**
     * @param Phrase|string|null $error
     * @return bool
     */
    public function canWarn(&$error = null)
    {
        if ($this->User && $this->User->hasPermission('forum', 'prevent_warning'))
        {
            return false;
        }

        return parent::canWarn($error);
    }
}
