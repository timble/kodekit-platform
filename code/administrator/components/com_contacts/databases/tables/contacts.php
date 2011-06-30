<?php
/**
 * @version     $Id: templates.php 1161 2011-05-11 14:52:09Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Contacts Database Table Class
 *
 * @author      Isreal Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts   
 */

class ComContactsDatabaseTableContacts extends KDatabaseTableDefault
{
	public function _initialize(KConfig $config)
	{
		$config->identity_column = 'id';
		
		$sluggable = KDatabaseBehavior::factory('sluggable', array('columns' => array('name')));

        $config->append(array(
            'name'      => 'contact_details',
            'behaviors' => array('orderable', 'lockable', $sluggable),
            'column_map'=> array(
                'enabled' 	=> 'published',
				'locked_on' => 'checked_out_time',
				'locked_by'	=> 'checked_out',
				'slug'		=> 'alias',
                'category'  => 'catid',
            ),
             'filters' => array(
                'params'    => 'ini'
            )
        ));
        
		parent::_initialize($config);
	}
}
