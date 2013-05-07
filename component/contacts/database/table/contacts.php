<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Contacts;

use Nooku\Library;

/**
 * Contacts Database Table
 *
 * @author  Isreal Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @package Nooku\Component\Contacts
 */
class DatabaseTableContacts extends Library\DatabaseTableDefault
{
	public function _initialize(Library\ObjectConfig $config)
	{
        $config->append(array(
            'name' => 'contacts',
            'behaviors' => array(
            	'creatable', 'modifiable', 'orderable', 'lockable', 
                'sluggable' => array('columns' => array('name'))
            ),
             'filters' => array(
                'params'    => 'ini'
            )
        ));
        
		parent::_initialize($config);
	}
}
