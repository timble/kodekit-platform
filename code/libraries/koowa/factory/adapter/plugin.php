<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Factory Adapter for a plugin
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Adapter
 */
class KFactoryAdapterPlugin extends KFactoryAdapterAbstract
{

	/**
	 * Create an instance of a class based on a class identifier
	 * 
	 * @param  mixed  		 Identifier or Identifier object - plg.type.plugin.[.path].name
	 * @param  object 		 An optional KConfig object with configuration options
	 * @return object|false  Return object on success, returns FALSE on failure
	 */
	public function instantiate($identifier, KConfig $config)
	{
		$classname = false;
		
		if($identifier->type == 'plg') 
		{			
			$classpath = KInflector::camelize(implode('_', $identifier->path));
			$classname = 'Plg'.ucfirst($identifier->package).$classpath.ucfirst($identifier->name);
			
			//Don't allow the auto-loader to load plugin classes if they don't exists yet
			if (!class_exists( $classname, false ))
			{
				//Find the file
				if($path = KLoader::load($identifier))
				{
					//Don't allow the auto-loader to load plugin classes if they don't exists yet
					if (!class_exists( $classname, false )) {
						throw new KFactoryAdapterException("Class [$classname] not found in file [".$path."]" );
					}
				}
			}
		}

		return $classname;
	}
}