<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Factory Adapter for the Koowa framework
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Adapter
 * @uses 		KInflector
 */
class KFactoryAdapterKoowa extends KFactoryAdapterAbstract
{
	/**
	 * Create an instance of a class based on a class identifier
	 *
	 * @param mixed  Identifier or Identifier object - lib.koowa.[.path].name
	 * @param array  An optional associative array of configuration settings.
	 * @return object|false  Return object on success, returns FALSE on failure
	 */
	public function instantiate($identifier, array $options)
	{
		$instance = false;

		if($identifier->type == 'lib' && $identifier->package == 'koowa')
		{
			$classname = 'K'.KInflector::implode($identifier->path).ucfirst($identifier->name);
			
			if (!class_exists($classname))
			{
				// use default class instead
				$classname = 'K'.KInflector::implode($identifier->path).'Default';
				
				if (!class_exists($classname)) {
					throw new KFactoryAdapterException("Could't create instance for $identifier");
				}
			}

			//If the object is indentifiable push the identifier in through the constructor
			if(array_key_exists('KFactoryIdentifiable', class_implements($classname))) {
				$options['identifier'] = $identifier;
			}
			
			// If the class has a factory method call it
			if(is_callable(array($classname, 'factory'), false)) {
				$instance = call_user_func(array($classname, 'factory'), $options);
			} else {
				$instance = new $classname($options);
			}
		}

		return $instance;
	}
}