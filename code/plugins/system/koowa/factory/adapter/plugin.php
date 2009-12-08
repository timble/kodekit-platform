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
 * Factory Adapter for a plugin
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Adapter
 */
class KFactoryAdapterPlugin extends KFactoryAdapterAbstract
{

	/**
	 * Create an instance of a class based on a class identifier
	 * 
	 *
	 * @param mixed  		 Identifier or Identifier object - plg.type.plugin.[.path].name
	 * @param array  		 An optional associative array of configuration settings.
	 * @return object|false  Return object on success, returns FALSE on failure
	 */
	public function instantiate($identifier, array $options)
	{
		$instance = false;
		
		if($identifier->type == 'plg') 
		{			
			$path      = KInflector::camelize(implode('_', $identifier->path));
			$classname = 'Plg'.ucfirst($identifier->package).$path.ucfirst($identifier->name);
			
      		//Don't allow the auto-loader to load plugin classes if they don't exists yet
			if (!class_exists( $classname)) {
				throw new KFactoryAdapterException("Class [$classname] not found in file [".KLoader::path($identifier)."]" );
			}
			
			//If the object is indentifiable push the identifier in through the constructor
			if(array_key_exists('KFactoryIdentifiable', class_implements($classname))) 
			{
				$identifier->filepath = KLoader::path($identifier);
				$options['identifier'] = $identifier;
			}
							
			$instance = new $classname($options);
		}

		return $instance;
	}
}