<?php

namespace SV\CanWarnStaff\XF\Pub\Controller;

use SV\CanWarnStaff\Globals;
use XF\Mvc\ParameterBag;

/**
 * @extends \XF\Pub\Controller\Thread
 */
class Thread extends XFCP_Thread
{
    public function actionReplyBans(ParameterBag $params)
    {
        Globals::$permCheckInDelete = true;
        try
        {
            return parent::actionReplyBans($params);
        }
        finally
        {
            Globals::$permCheckInDelete = false;
        }
    }
}