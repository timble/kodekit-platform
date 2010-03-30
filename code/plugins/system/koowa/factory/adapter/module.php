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
 * Factory Adapter for a plugin
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Adapter
 */
class KFactoryAdapterModule extends KFactoryAdapterAbstract
{

	/**
	 * Create an instance of a class based on a class identifier
	 *
	 * @param mixed  		 Identifier or Identifier object - application::mod.module.[.path].name
	 * @param 	object 		 An optional KConfig object with configuration options
	 * @return object|false  Return object on success, returns FALSE on failure
	 */
	public function instantiate($identifier, KConfig $config)
	{
		$instance = false;
		
		if($identifier->type == 'mod') 
		{			
			$classpath = KInflector::camelize(implode('_', $identifier->path));
			$classname = 'Mod'.ucfirst($identifier->package).$classpath.ucfirst($identifier->name);
			
      		//Don't allow the auto-loader to load module classes if they don't exists yet
			if (!$path = KLoader::load( $classname )) {
				throw new KFactoryAdapterException("Class [$classname] not found in file [".$path."]" );
			}
			
			//If the object is indentifiable push the identifier in through the constructor
			if(array_key_exists('KObjectIdentifiable', class_implements($classname))) 
			{
				$identifier->filepath = $path;
				$config->identifier = $identifier;
			}
							
			$instance = new $classname($config);
		}

		return $instance;
	}
}