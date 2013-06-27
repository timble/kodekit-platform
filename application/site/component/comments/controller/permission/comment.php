<?php
/**
 * @package        Nooku_Server
 * @subpackage     Comments
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Library;

/**
 * Comment Controller Permission Class
 *
 * @author     Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package    Nooku_Server
 * @subpackage Comments
 */
class CommentsControllerPermissionComment extends ApplicationControllerPermissionDefault
{
    public function canEdit()
    {
        $result  = false;
        $comment = $this->getModel()->getRow();

        //If the user is manager he can moderator comments
        if($this->getUser()->getRole() >= 23) {
            $result = true;
        }

        //If the user is the owner of a comment he can edit.
        if($comment->created_by == $this->getUser()->getId()) {
            $result = true;
        }

        return $result;
    }

    public function canDelete()
    {
        $comment = $this->getModel()->getRow();
        $result = false;

        //If the user is the owner of a comment he delete it.
        if($comment->created_by == $this->getUser()->getId()) {
            $result = true;
        }

        //If the user is manager he can delete comments
        if($this->getUser()->getRole() >= 23) {
            $result = true;
        }

        return $result;
    }
}