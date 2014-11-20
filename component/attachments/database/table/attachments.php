<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Attachments;

use Nooku\Library;

/**
 * Attachments Database Table
 *
 * @author  Steven Rombauts <http://github.com/stevenrombauts>
 * @package Nooku\Component\Attachments
 */
class DatabaseTableAttachments extends Library\DatabaseTableAbstract
{
	protected function _initialize(Library\ObjectConfig $config)
    {
    	$config->append(array(
    		'name'      => 'attachments',
    		'behaviors' => array('creatable', 'modifiable', 'lockable', 'identifiable'),
    	    'filters'   => array(
                'description' => array('html', 'tidy')
            )
    	));
    	
		parent::_initialize($config);
    }
}