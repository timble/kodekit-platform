<?php
/**
 * @version    	$Id$
 * @category	Nooku
 * @package    	Nooku_Server
 * @subpackage 	Categories
 * @copyright  	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license    	GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link       	http://www.nooku.org
 */

/**
 * Contacts Database Table Class
 *
 * @author		Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Categories    
 */
class ComContactDatabaseTableContacts extends KDatabaseTableDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_column_map = array_merge(
			$this->_column_map,
			array(
				'enabled' 	=> 'published',
				'locked_on' => 'checked_out_time',
				'locked_by'	=> 'checked_out',
				'slug'		=> 'alias'
			)
		);

	}
	
	public function _initialize(KConfig $config)
	{
		$config->identity_column = 'id';
		$config->name = 'contact_details';
		$config->base = 'contact_details';

		$config->behaviors = array('orderable', 'lockable');

		parent::_initialize($config);
	}
}
