<?php

namespace SV\CanWarnStaff\SV\ModeratorEssentials\Pub\Controller;

use SV\CanWarnStaff\Globals;

class Moderator extends XFCP_Moderator
{
    public function actionThreadReplyBanDelete()
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