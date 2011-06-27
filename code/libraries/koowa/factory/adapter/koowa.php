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
 * Factory Adapter for the Koowa framework
 *
 * @author		Johan Janssens <johan@nooku.org>
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
	 * @param 	mixed  		 Identifier or Identifier object - lib.koowa.[.path].name
	 * @param 	object 		 An optional KConfig object with configuration options
	 * @return object|false  Return object on success, returns FALSE on failure
	 */
	public function instantiate($identifier, KConfig $config)
	{
		$classname = false;

		if($identifier->type == 'lib' && $identifier->package == 'koowa')
		{
			$classname = 'K'.KInflector::implode($identifier->path).ucfirst($identifier->name);
			$filepath  = KLoader::path($identifier);
			
			if (!class_exists($classname))
			{
				// use default class instead
				$classname = 'K'.KInflector::implode($identifier->path).'Default';
				
				if (!class_exists($classname)) {
					throw new KFactoryAdapterException("Class [$classname] not found in file [".basename($filepath)."]" );
				}
			}
		}

		return $classname;
	}
}