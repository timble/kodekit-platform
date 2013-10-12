<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Comments;

/**
 * Comment Controller
 *
 * @author    	Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package     Component\Comments
 */
class CommentsControllerComment extends Comments\ControllerComment
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array(
                'editable'
            ),
            'model' => 'com:comments.model.comments'
        ));

        parent::_initialize($config);
    }
}