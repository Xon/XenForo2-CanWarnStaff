<?php

namespace SV\CanWarnStaff\SV\ModeratorEssentials\Pub\Controller;

use SV\CanWarnStaff\Globals;
use XF\Mvc\Reply\AbstractReply;

/**
 * @extends \SV\ModeratorEssentials\Pub\Controller\Moderator
 */
class Moderator extends XFCP_Moderator
{
    public function actionThreadReplyBanDelete(): AbstractReply
    {
        Globals::$permCheckInDelete = true;
        try
        {
            return parent::actionThreadReplyBanDelete();
        }
        finally
        {
            Globals::$permCheckInDelete = false;
        }
    }
}