<?php


namespace SV\CanWarnStaff\XF\Repository;

use XF\Mvc\Entity\Entity;

class Attachment extends XFCP_Attachment
{
    public function addAttachmentsToContent($content, $contentType, $countKey = 'attach_count', $relationKey = 'Attachments')
    {
        parent::addAttachmentsToContent($content, $contentType, $countKey, $relationKey);;
        if($contentType === 'post')
        {
            $permCombIds = [];
            foreach ($content AS $id => $item)
            {
                /** @var Entity $item*/
                $permCombIds[] = $item->getRelation('User')->getValue('permission_combination_id');
            }
            $uniquePermCombIds = array_unique($permCombIds);
            /** @var \SV\CanWarnStaff\XF\Repository\User $userRepo */
            $userRepo = \XF::repository('XF:User');
            $userRepo->preloadGlobalPermissionsFromIds($uniquePermCombIds);
        }
    }
}
