<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Comment Controller Permission
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Comments
 */
class CommentsControllerPermissionComment extends ApplicationControllerPermissionAbstract
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