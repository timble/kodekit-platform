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
 * Identifier Adapter for a component
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Identifier
 * @subpackage 	Adapter
 */
class KIdentifierAdapterComponent extends KIdentifierAdapterAbstract
{
	/** 
	 * The adapter type
	 * 
	 * @var string
	 */
	protected $_type = 'com';
	
	/**
	 * Get the classname based on an identifier
	 * 
	 * This factory will try to create an generic or default classname on the identifier information
	 * if the actual class cannot be found using a predefined fallback sequence.
	 * 
	 * Fallback sequence : -> Named Component Specific
	 *                     -> Named Component Default  
	 *                     -> Default Component Specific
	 *                     -> Default Component Default
	 *                     -> Framework Specific 
	 *                     -> Framework Default
	 *
	 * @param mixed  		 Identifier or Identifier object - com:[//application/]component.view.[.path].name
	 * @return string|false  Return object on success, returns FALSE on failure
	 */
	public function findClass(KIdentifier $identifier)
	{ 
	    $path      = KInflector::camelize(implode('_', $identifier->path));
        $classname = 'Com'.ucfirst($identifier->package).$path.ucfirst($identifier->name);
        
      	//Manually load the class to set the basepath
		if (!KLoader::loadClass($classname, $identifier->basepath))
		{
		    $classpath = $identifier->path;
			$classtype = !empty($classpath) ? array_shift($classpath) : '';
					
			//Create the fallback path and make an exception for views
			$path = ($classtype != 'view') ? KInflector::camelize(implode('_', $classpath)) : '';
						
			/*
			 * Find the classname to fallback too and auto-load the class
			 * 
			 * Fallback sequence : -> Named Component Specific 
			 *                     -> Named Component Default  
			 *                     -> Default Component Specific 
			 *                     -> Default Component Defaukt
			 *                     -> Framework Specific 
			 *                     -> Framework Default
			 */
			if(class_exists('Com'.ucfirst($identifier->package).ucfirst($classtype).$path.ucfirst($identifier->name))) {
				$classname = 'Com'.ucfirst($identifier->package).ucfirst($classtype).$path.ucfirst($identifier->name);
			} elseif(class_exists('Com'.ucfirst($identifier->package).ucfirst($classtype).$path.'Default')) {
				$classname = 'Com'.ucfirst($identifier->package).ucfirst($classtype).$path.'Default';
			} elseif(class_exists('ComDefault'.ucfirst($classtype).$path.ucfirst($identifier->name))) {
				$classname = 'ComDefault'.ucfirst($classtype).$path.ucfirst($identifier->name);
			} elseif(class_exists('ComDefault'.ucfirst($classtype).$path.'Default')) {
				$classname = 'ComDefault'.ucfirst($classtype).$path.'Default';
			} elseif(class_exists( 'K'.ucfirst($classtype).$path.ucfirst($identifier->name))) {
				$classname = 'K'.ucfirst($classtype).$path.ucfirst($identifier->name);
			} elseif(class_exists('K'.ucfirst($classtype).$path.'Default')) {
				$classname = 'K'.ucfirst($classtype).$path.'Default';
			} else {
				$classname = false;
			}
		}
		
		return $classname;
	}
	
	/**
	 * Get the path based on an identifier
	 *
	 * @param  object  	An Identifier object - com:[//application/]component.view.[.path].name
	 * @return string	Returns the path
	 */
	public function findPath(KIdentifier $identifier)
	{
		$parts = $identifier->path;
				
		$component = 'com_'.strtolower($identifier->package);
			
		if(!empty($identifier->name))
		{
			if(count($parts)) 
			{
				$path    = KInflector::pluralize(array_shift($parts));
				$path   .= count($parts) ? '/'.implode('/', $parts) : '';
				$path   .= '/'.strtolower($identifier->name);	
			} 
			else $path  = strtolower($identifier->name);	
		}
				
		$path = $identifier->basepath.'/components/'.$component.'/'.$path.'.php';	
		return $path;
	}
}