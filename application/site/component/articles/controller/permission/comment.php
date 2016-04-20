<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Comments;

use Kodekit\Library;
use Kodekit\Platform\Application;

/**
 * Comment Controller Permission
 *
 * @author  Terry Visser <http://github.com/terryvisser>
 * @package Kodekit\Platform\Comments
 */
class ControllerPermissionComment extends Application\ControllerPermissionAbstract
{
    public function canAdd()
    {
        $result = false;

        // Logged in users can add comments
        if($this->getUser()->getId()){
            $result = true;
        }

        return $result;
    }

    public function canEdit()
    {
        $result  = false;
        $comment = $this->getModel()->fetch();

        // If the user is manager he can moderator comments
        if($this->getUser()->hasRole('manager', 'administrator')) {
            $result = true;
        }

        // If the user is the creator of a comment he can moderator it
        if($comment->created_by == $this->getUser()->getId()) {
            $result = true;
        }

        return $result;
    }

    public function canDelete()
    {
        $comment = $this->getModel()->fetch();
        $result = false;

        // If the user is author he can delete comments
        if($this->getUser()->hasRole(array('author', 'editor', 'publisher', 'manager', 'administrator'))) {
            $result = true;
        }

        // If the user is the creator of a comment he can delete it
        if($comment->created_by == $this->getUser()->getId()) {
            $result = true;
        }

        return $result;
    }
}