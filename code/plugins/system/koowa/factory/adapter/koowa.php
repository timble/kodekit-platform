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
 * Factory Adpater for the Koowa framework
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
	 * @param mixed  The class identifier
	 * @param array  An optional associative array of configuration settings.
	 * @return object|false  Return object on success, returns FALSE on failure
	 */
	public function instantiate($identifier, array $options)
	{
		$instance = false;

		// we accept either a string or an identifier object.
		if(!($identifier instanceof KFactoryIdentifierInterface)) {
			$identifier = new KFactoryIdentifierKoowa($identifier);
		}

		if($identifier->extension == 'lib' && $identifier->library == 'koowa')
		{
			$classname = $identifier->getClassName();

			if (!class_exists($classname))
			{
				// use default class instead
				$classname = $identifier->getDefaultClass();
				if (!class_exists($classname)) {
					throw new KFactoryAdapterException("Could't create instance for $identifier");
				}
			}

			$options['identifier'] = $identifier;

			// If the class has a factory method call it
			if(is_callable(array($classname, 'factory'), false, $function)) {
				$instance = call_user_func($function, $options);
			} else {
				$instance = new $classname($options);
			}
		}

		return $instance;
	}
}