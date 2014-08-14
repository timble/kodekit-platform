<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Comments;

use Nooku\Library;

/**
 * Comments Database Table
 *
 * @author  Steven Rombauts <http://github.com/stevenrombauts>
 * @package Nooku\Component\Comments
 */
class DatabaseTableComments extends Library\DatabaseTableAbstract
{
	protected function _initialize(Library\ObjectConfig $config)
    {
    	$config->append(array(
    		'name'      => 'comments',
    		'behaviors' => array('creatable', 'modifiable', 'lockable', 'identifiable'),
            'filters' => array(
                'text'   => array('html', 'tidy'),

            )
    	));
    	
		parent::_initialize($config);
    }
}