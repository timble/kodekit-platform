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
	 * The adapter type
	 * 
	 * @var string
	 */
	protected $_type = 'koowa';
	
	/**
	 * Create an instance of a class based on a class identifier
	 *
	 * @param 	mixed  		 Identifier or Identifier object - koowa.[.path].name
	 * @param 	object 		 An optional KConfig object with configuration options
	 * @return object|false  Return object on success, returns FALSE on failure
	 */
	public function instantiate($identifier, KConfig $config)
	{
        $classname = 'K'.ucfirst($identifier->package).KInflector::implode($identifier->path).ucfirst($identifier->name);
		$filepath  = KLoader::path($identifier);
			
		if (!class_exists($classname))
		{
			// use default class instead
			$classname = 'K'.ucfirst($identifier->package).KInflector::implode($identifier->path).'Default';
				
			if (!class_exists($classname)) {
				throw new KFactoryAdapterException("Class [$classname] not found in file [".basename($filepath)."]" );
			}
		}

		return $classname;
	}
}