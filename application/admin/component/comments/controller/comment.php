<?php
/**
 * @package     Nooku_Server
 * @subpackage  Comments
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;
use Nooku\Component\Comments;

/**
 * Comment Controller Class
 *
 * @author    	Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package     Nooku_Server
 * @subpackage  Comments
 */
class CommentsControllerComment extends Comments\ControllerComment
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array('com:activities.controller.behavior.loggable'),
        ));

        //Force the toolbars
        $config->toolbars = array('menubar', 'com:comments.controller.toolbar.comment');

        parent::_initialize($config);
    }
}