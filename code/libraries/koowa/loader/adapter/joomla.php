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
 * Loader Adapter for the Joomla! framework
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Loader
 * @subpackage 	Adapter
 */
class KLoaderAdapterJoomla extends KLoaderAdapterAbstract
{
	/**
	 * The prefix
	 * 
	 * @var string
	 */
	protected $_prefix = 'J';
	
	
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
		
		$word  = preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $classname);
		$parts = explode('_', $word);
			
		// If class start with a 'J' it is a Joomla framework class and we handle it
		if(array_shift($parts) == $this->_prefix)
		{
			$class = strtolower($classname); //force to lower case

			if (class_exists($class)) {
				 return;
			}

			$classes = JLoader::register();
			if(array_key_exists( $class, $classes)) {
				$path = $classes[$class];
			}
		} 
		
		return $path;
	}
	
	/**
	 * Get the path based on an identifier
	 *
	 * @param  object  			An Identifier object - lib.joomla.[.path].name
	 * @return string|false		Returns the path on success FALSE on failure
	 */
	protected function _pathFromIdentifier($identifier)
	{
		$path = false;
		
		if($identifier->type == 'lib' && $identifier->package == 'joomla')
		{
			if(count($identifier->path)) {
				$path .= implode('.',$identifier->path);
			}

			if(!empty($identifier->name)) {
				$path .= '.'.$identifier->name;
			}
				
			$path = JLoader::import('joomla.'.$path );
		}

		return $path;
	}
}