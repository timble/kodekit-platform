<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-comments for the canonical source repository
 */

namespace Kodekit\Component\Comments;

use Kodekit\Library;

/**
 * Comments Database Table
 *
 * @author  Steven Rombauts <http://github.com/stevenrombauts>
 * @package Kodekit\Component\Comments
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