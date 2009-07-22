<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
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
	 * @param mixed  Identifier or Identifier object - lib.joomla.[.path].name
	 * @param array  An optional associative array of configuration settings.
	 * @return object|false  Return object on success, returns FALSE on failure
	 */
	public function instantiate($identifier, array $options)
	{
		$instance = false;

		if($identifier->type == 'lib' && $identifier->package == 'joomla')
		{
			$name = ucfirst($identifier->name);
			
			//Check to see of the type is an alias
			if(array_key_exists($name, $this->_alias_map)) {
				$name = $this->_alias_map[$name];
			}

			$instance = call_user_func_array(array('JFactory', 'get'.$name), $options);
		}

		return $instance;
	}
}