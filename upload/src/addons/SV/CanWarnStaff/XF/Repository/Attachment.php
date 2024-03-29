<?php


namespace SV\CanWarnStaff\XF\Repository;

use XF\Entity\Post as PostEntity;

class Attachment extends XFCP_Attachment
{
    protected $preloadPermissionsCombinationIds = [];

    public function addAttachmentsToContent($content, $contentType, $countKey = 'attach_count', $relationKey = 'Attachments')
    {
        $content = parent::addAttachmentsToContent($content, $contentType, $countKey, $relationKey);

        if ($contentType === 'post')
        {
            $visitor = \XF::visitor();
            $doPermCheck = true;
            foreach ($content AS $item)
            {
                /** @var PostEntity $item */
                $user = $item->getRelation('User');
                $thread = $item->Thread;
                if ($user !== null && $thread !== null)
                {
                    if ($doPermCheck)
                    {
                        $doPermCheck = false;
                        if (!$visitor->hasNodePermission($thread->node_id, 'warn'))
                        {
                            break;
                        }
                    }
                    $this->preloadPermissionsCombinationIds[(int)$user->getValue('permission_combination_id')] = true;
                }
            }

            if ($this->preloadPermissionsCombinationIds)
            {
                \XF::runLater(function () {
                    if ($this->preloadPermissionsCombinationIds)
                    {
                        /** @var User $userRepo */
                        $userRepo = \XF::repository('XF:User');
                        $userRepo->preloadGlobalPermissionsFromIds(\array_keys($this->preloadPermissionsCombinationIds));
                        $this->preloadPermissionsCombinationIds = [];
                    }
                });
            }
        }

        return $content;
    }
}
