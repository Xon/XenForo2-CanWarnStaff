<?php


namespace SV\CanWarnStaff\XF\Repository;

use XF\Mvc\Entity\Entity;

class Attachment extends XFCP_Attachment
{
    public function addAttachmentsToContent($content, $contentType, $countKey = 'attach_count', $relationKey = 'Attachments')
    {
        parent::addAttachmentsToContent($content, $contentType, $countKey, $relationKey);
        if ($contentType === 'post')
        {
            $visitor = \XF::visitor();
            $doPermCheck = true;
            $permCombIds = [];
            foreach ($content AS $id => $item)
            {
                /** @var Entity|\XF\Entity\Post $item */
                $user = $item->getRelation('User');
                if ($user)
                {
                    if ($doPermCheck)
                    {
                        $doPermCheck = false;
                        /** @noinspection PhpUndefinedMethodInspection */
                        if (!$visitor->hasNodePermission($item->Thread->node_id, 'warn'))
                        {
                            break;
                        }
                    }
                    $permCombIds[] = $user->getValue('permission_combination_id');
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
    }
}
