<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Comments;

use Nooku\Framework;

/**
 * Comments Database Table
 *
 * @author  Steven Rombauts <https://nooku.assembla.com/profile/stevenrombauts>
 * @package Nooku\Component\Comments
 */
class DatabaseTableComments extends Framework\DatabaseTableDefault
{
	protected function _initialize(Framework\Config $config)
    {
    	$config->append(array(
    		'name'      => 'comments',
    		'behaviors' => array('creatable', 'modifiable', 'lockable'),
    	    'filters'   => array(
                'text' => array('html', 'tidy')
            )
    	));
    	
		parent::_initialize($config);
    }
}