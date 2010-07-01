<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Loader
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Loader Adapter for a plugin
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Loader
 * @subpackage 	Adapter
 * @uses		KInflector
 */
class KLoaderAdapterPlugin extends KLoaderAdapterAbstract
{
	/**
	 * The basepath 
	 * 
	 * @var string
	 */
	protected $_basepath = JPATH_PLUGINS;
	
	/**
	 * The prefix
	 * 
	 * @var string
	 */
	protected $_prefix = 'Plg';
	
	
	/**
	 * Get the class prefix
	 *
	 * @return string	Returns the class prefix
	 */
	public function getPrefix()
	{
		return $this->_prefix;
	}
	
	/**
	 * Get the path based on a class name
	 *
	 * @param  string		  	The class name 
	 * @return string|false		Returns the path on success FALSE on failure
	 */
	protected function _pathFromClassname($classname)
	{	
		$path = false; 
		
		if (strpos($classname, $this->_prefix) === 0) 
		{	
			$word  = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $classname));
			$parts = explode('_', $word);
			
			if (array_shift($parts) == 'plg') 
			{	
				$type = array_shift($parts);
				
				if(count($parts) > 1) {
					$path = array_shift($parts).'/'.implode('/', $parts);
				} else {
					$path = array_shift($parts);
				}
					
				$path = $this->_basepath.'/'.$type.'/'.$path.'.php';			
			}
		}
		
		return $path;
		
	}

	/**
	 * Get the path based on an identifier
	 *
	 * @param  object  			An Identifier object - plg.type.plugin.[.path].name
	 * @return string|false		Returns the path on success FALSE on failure
	 */
	protected function _pathFromIdentifier($identifier)
	{
		$path = false;
		
		if($identifier->type == 'plg')
		{		
			$parts = $identifier->path;
			
			$name  = array_shift($parts);
			$type  = $identifier->package;
			
			if(!empty($identifier->name))
			{
				if(count($parts)) 
				{
					$path    = array_shift($parts).
					$path   .= count($parts) ? '/'.implode('/', $parts) : '';
					$path   .= DS.strtolower($identifier->name);	
				} 
				else $path  = strtolower($identifier->name);	
			}
				
			$path = $this->_basepath.'/'.$type.'/'.$name.'/'.$path.'.php';	
		}	
		
		return $path;
	}		
}