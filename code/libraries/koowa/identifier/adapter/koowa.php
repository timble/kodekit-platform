<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Identifier
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Identifier Adapter for the Koowa framework
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Identifier
 * @subpackage 	Adapter
 * @uses 		KInflector
 */
class KIdentifierAdapterKoowa extends KIdentifierAdapterAbstract
{
	/** 
	 * The adapter type
	 * 
	 * @var string
	 */
	protected $_type = 'koowa';
	
	/**
	 * Get the classname based on an identifier
	 *
	 * @param 	mixed  		 Identifier or Identifier object - koowa.[.path].name
	 * @return string|false  Return object on success, returns FALSE on failure
	 */
	public function findClass(KIdentifier $identifier)
	{
        $classname = 'K'.ucfirst($identifier->package).KInflector::implode($identifier->path).ucfirst($identifier->name);
			
		if (!class_exists($classname))
		{
			// use default class instead
			$classname = 'K'.ucfirst($identifier->package).KInflector::implode($identifier->path).'Default';
				
			if (!class_exists($classname)) {
				$classname = false;
			}
		}
		
		return $classname;
	}
	
	/**
	 * Get the path based on an identifier
	 *
	 * @param  object  	Identifier or Identifier object - koowa.[.path].name
	 * @return string	Returns the path
	 */
	public function findPath(KIdentifier $identifier)
	{
		if(count($identifier->path)) {
			$path .= implode('/',$identifier->path);
		}

		if(!empty($identifier->name)) {
			$path .= '/'.$identifier->name;
		}
				
		$path = $identifier->basepath.'/'.$path.'.php';
		return $path;
	}
}