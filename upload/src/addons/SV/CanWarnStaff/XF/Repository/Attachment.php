<?php


namespace SV\CanWarnStaff\XF\Repository;

use SV\StandardLib\Helper;
use XF\Entity\Post as PostEntity;
use SV\CanWarnStaff\XF\Repository\User as ExtendedUserRepo;
use XF\Repository\User as UserRepo;

/**
 * @extends \XF\Repository\Attachment
 */
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
                        /** @var ExtendedUserRepo $userRepo */
                        $userRepo = Helper::repository(UserRepo::class);
                        $userRepo->preloadGlobalPermissionsFromIds(\array_keys($this->preloadPermissionsCombinationIds));
                        $this->preloadPermissionsCombinationIds = [];
                    }
                });
            }
        }

        return $content;
    }
}
