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
class KLoaderAdapterModule extends KLoaderAdapterAbstract
{
	/**
	 * The prefix
	 * 
	 * @var string
	 */
	protected $_prefix = 'Mod';
	
	
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
	 * The basepath 
	 * 
	 * @var string
	 */
	protected $_basepath = JPATH_BASE;
	
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
			
			if (array_shift($parts) == 'mod') 
			{	
				$name = array_shift($parts);
				
				if(count($parts) > 1) {
					$path = KInflector::pluralize(array_shift($parts)).DS.implode(DS, $parts);
				} else {
					$path = array_shift($parts);
				}
					
				$path = $this->_basepath.DS.'modules'.DS.'mod_'.$name.DS.$path.'.php';			
			}
		}
		
		return $path;
		
	}

	/**
	 * Get the path based on an identifier
	 *
	 * @param  object  			An Identifier object - application::mod.module.[.path].name
	 * @return string|false		Returns the path on success FALSE on failure
	 */
	protected function _pathFromIdentifier($identifier)
	{
		$path = false;
		
		if($identifier->type == 'mod')
		{		
			$parts = $identifier->path;
			$name  = $identifier->package;
			
			//Store the basepath for re-use
			$this->_setBasePath($identifier);
				
			if(!empty($identifier->name))
			{
				if(count($parts)) 
				{
					$path    = KInflector::pluralize(array_shift($parts)).
					$path   .= count($parts) ? DS.implode(DS, $parts) : '';
					$path   .= DS.strtolower($identifier->name);	
				} 
				else $path  = strtolower($identifier->name);	
			}
				
			$path = $this->_basepath.DS.'modules'.DS.'mod_'.$name.DS.$path.'.php';			
		}	
		
		return $path;
	}

	/**
	 * Set the base path
	 *
	 * @param  object  	The class name or an identifier
	 */
	protected function _setBasePath($identifier)
	{
		if(!$app = $identifier->application) {
			$app = KFactory::get('lib.koowa.application')->getName();
		}
		
		switch($app)
		{
			case 'admin' : $this->_basepath = JPATH_ADMINISTRATOR; break;
			case 'site'  : $this->_basepath = JPATH_SITE;		   break;
 		}
		
		return $this;
	}
}