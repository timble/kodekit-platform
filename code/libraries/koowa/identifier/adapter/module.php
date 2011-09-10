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
 * Identifier Adapter for a plugin
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Identifier
 * @subpackage 	Adapter
 */
class KIdentifierAdapterModule extends KIdentifierAdapterAbstract
{
	/** 
	 * The adapter type
	 * 
	 * @var string
	 */
	protected $_type = 'mod';
	
	/**
	 * Get the classname based on an identifier
	 * 
	 * This factory will try to create an generic or default classname on the identifier information
	 * if the actual class cannot be found using a predefined fallback sequence.
	 * 
	 * Fallback sequence : -> Named Module
	 *                     -> Default Module
	 *                     -> Framework Specific
	 *                     -> Framework Default
	 *
	 * @param mixed  		 Identifier or Identifier object - application::mod.module.[.path].name
	 * @return string|false  Return object on success, returns FALSE on failure
	 */
	public function findClass(KIdentifier $identifier)
	{		
		$path = KInflector::camelize(implode('_', $identifier->path));
		$classname = 'Mod'.ucfirst($identifier->package).$path.ucfirst($identifier->name);
				
		//Don't allow the auto-loader to load module classes if they don't exists yet
		if (!KLoader::loadClass($classname, $identifier->basepath))
		{
			$classpath = $identifier->path;
			$classtype = !empty($classpath) ? array_shift($classpath) : $identifier->name;
				
			//Create the fallback path and make an exception for views
			$path = ($classtype != 'view') ? KInflector::camelize(implode('_', $classpath)) : '';
					
			/*
			 * Find the classname to fallback too and auto-load the class
		     * 
			 * Fallback sequence : -> Named Module
			 *                     -> Default Module
			 *                     -> Framework Specific 
			 *                     -> Framework Default
			 */
			if(class_exists('Mod'.ucfirst($identifier->package).ucfirst($identifier->name))) {
				$classname = 'Mod'.ucfirst($identifier->package).ucfirst($identifier->name);
			} elseif(class_exists('ModDefault'.ucfirst($identifier->name))) {
				$classname = 'ModDefault'.ucfirst($identifier->name);
			} elseif(class_exists( 'K'.ucfirst($classtype).$path.ucfirst($identifier->name))) {
				$classname = 'K'.ucfirst($classtype).$path.ucfirst($identifier->name);
			} elseif(class_exists('K'.ucfirst($classtype).'Default')) {
				$classname = 'K'.ucfirst($classtype).'Default';
			} else {
				$classname = false;
			}
		
		}
	    
		return $classname;
	}
	
	/**
	 * Get the path based on an identifier
	 *
	 * @param  object  	An Identifier object - application::mod.module.[.path].name
	 * @return string	Returns the path
	 */
	public function findPath(KIdentifier $identifier)
	{
		$parts = $identifier->path;
		$name  = $identifier->package;
				
		if(!empty($identifier->name))
		{
			if(count($parts)) 
			{
				$path    = KInflector::pluralize(array_shift($parts)).
				$path   .= count($parts) ? '/'.implode('/', $parts) : '';
				$path   .= DS.strtolower($identifier->name);	
			} 
			else $path  = strtolower($identifier->name);	
		}
				
		$path = $identifier->basepath.'/modules/mod_'.$name.'/'.$path.'.php';			
	    return $path;
	}
}