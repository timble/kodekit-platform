<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Loader
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Loader Adapter for the Koowa framework
 *
 * @author		Johan Janssens <johan@nooku.org>
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
			$path = strtolower(implode('/', $parts));
				
			if(count($parts) == 1) {
				$path = $path.'/'.$path;
			}
			
			if(!is_file($this->_basepath.'/'.$path.'.php')) {
				$path = $path.'/'.strtolower(array_pop($parts));
			}

			$path = $this->_basepath.'/'.$path.'.php';
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
			if(count($identifier->path)) {
				$path .= implode('/',$identifier->path);
			}

			if(!empty($identifier->name)) {
				$path .= '/'.$identifier->name;
			}
				
			$path = $this->_basepath.'/'.$path.'.php';
		}
		
		return $path;
	}
}