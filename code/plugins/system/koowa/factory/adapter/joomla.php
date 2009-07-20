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
	 * Create an instance of a class based on a class identifier
	 *
	 * @param mixed  Identifier or Identifier object - lib.joomla.[.path].name
	 * @param array  An optional associative array of configuration settings.
	 * @return object|false  Return object on success, returns FALSE on failure
	 */
	public function instantiate($identifier, array $options)
	{
		$instance = false;

		if($identifier->type == 'lib' && $identifier->component == 'joomla')
		{
			$name = ucfirst($identifier->name);

			//Handle exceptions
			if($name == 'Database') {
				$name = 'DBO';
			}

			if($name == 'Authorization') {
				$name = 'ACL';
			}

			$instance = call_user_func_array(array('JFactory', 'get'.$name), $options);
		}

		return $instance;
	}
}