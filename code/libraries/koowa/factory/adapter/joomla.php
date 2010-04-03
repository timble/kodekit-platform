<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Factory Adapter for the Joomla! framework
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Adapter
 */
class KFactoryAdapterJoomla extends KFactoryAdapterAbstract
{
	/**
	 * The alias type map
	 *
	 * @var	array
	 */
	protected $_alias_map = array(
      	'Database'  	=> 'DBO',
        'Authorization' => 'ACL',
      	'Xml'    		=> 'XMLParser'
	);

	/**
	 * Create an instance of a class based on a class identifier
	 *
	 * @param 	mixed  		 Identifier or Identifier object - lib.joomla.[.path].name
	 * @param 	object 		 An optional KConfig object with configuration options
	 * @return object|false  Return object on success, returns FALSE on failure
	 */
	public function instantiate($identifier, KConfig $config)
	{
		$instance = false;

		if($identifier->type == 'lib' && $identifier->package == 'joomla')
		{
			$name = ucfirst($identifier->name);
			
			//Check to see of the type is an alias
			if(array_key_exists($name, $this->_alias_map)) {
				$name = $this->_alias_map[$name];
			}

			$instance = call_user_func_array(array('JFactory', 'get'.$name), $config->toArray());
		}

		return $instance;
	}
}