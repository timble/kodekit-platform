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
 * Loader Adapter for the Koowa framework
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Loader
 * @subpackage 	Adapter
 * @uses 		Koowa
 */
class KLoaderAdapterKoowa extends KLoaderAdapterAbstract
{
	/**
	 * The prefix
	 * 
	 * @var string
	 */
	protected $_prefix = 'K';
	
	
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
		$path     = false;
		
		$word  = preg_replace('/(?<=\\w)([A-Z])/', '_\\1',  $classname);
		$parts = explode('_', $word);
		
		// If class start with a 'K' it is a Koowa framework class and we handle it
		if(array_shift($parts) == $this->_prefix)
		{	
			$basepath = Koowa::getPath();
			$path     = strtolower(implode(DS, $parts));
				
			if(count($parts) == 1) {
				$path = $path.DS.$path;
			}
			
			if(!is_file($basepath.DS.$path.'.php')) {
				$path = $path.DS.strtolower(array_pop($parts));
			}

			$path = $basepath.DS.$path.'.php';
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
		
		if($identifier->type == 'lib' && $identifier->package == 'koowa')
		{
			$basepath = Koowa::getPath();
			
			if(count($identifier->path)) {
				$path .= implode(DS,$identifier->path);
			}

			if(!empty($this->name)) {
				$path .= DS.$identifier->name;
			}
				
			$path = $basepath.DS.$path.'.php';
		}
		
		return $path;
	}
}