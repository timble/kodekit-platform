<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Factory
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Factory Adapter for a component
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Adapter
 */
class KFactoryAdapterComponent extends KFactoryAdapterAbstract
{
	/**
	 * The alias map
	 *
	 * @var	array
	 */
	protected $_alias_map = array(
      	'table'     => 'DatabaseTable',
        'row'       => 'DatabaseRow',
      	'rowset'    => 'DatabaseRowset'
	);


	/**
	 * Create an instance of a class based on a class identifier
	 *
	 * @param mixed  Identifier or Identifier object - application::com.component.[.path].name
	 * @param array  An optional associative array of configuration settings.
	 * @return object|false  Return object on success, returns FALSE on failure
	 */
	public function instantiate($identifier, array $options)
	{
		$instance = false;
		
		if($identifier->type == 'com') 
		{
			$classname = $this->_getClassName($identifier);
        	$filename  = $this->_getFileName($identifier);
        	$filepath  = $this->_getFilePath($identifier);
        	
      		if (!class_exists( $classname ))
			{
				//Find the file
				$file = $filepath.DS.$filename;
				if(file_exists($file))
				{
					include $file;
					if (!class_exists( $classname )) {
						throw new KFactoryAdapterException("Class [$classname] not found in file [$file]" );
					}
				}
				else 
				{
					$classpath = $identifier->path;
					$classtype = !empty($classpath) ? array_shift($classpath) : $identifier->name;
					
					//Check to see of the type is an alias
					if(array_key_exists($classtype, $this->_alias_map)) {
						$classtype = $this->_alias_map[$classtype];
					}
					
					//Create the classpath
					$path =  KInflector::camelize(implode('_', $classpath));
					
					//Create the classname
					if(class_exists( 'K'.ucfirst($classtype).$path.ucfirst($identifier->name))) {
						$classname = 'K'.ucfirst($classtype).$path.ucfirst($identifier->name);
					} else {
						$classname = 'K'.ucfirst($classtype).$path.'Default';
					}
				}
			}
			
			if(class_exists( $classname ))
			{
				//If the object is indentifiable push the identifier in through the constructor
				if(array_key_exists('KFactoryIdentifiable', class_implements($classname))) 
				{
					$identifier->filename = $filename;
					$identifier->filepath = $filepath;
					$options['identifier'] = $identifier;
				}
				
				$instance = new $classname($options);
			}
		}

		return $instance;
	}
	
	/**
	 * Get the class name of the object
	 *
	 * @return string
	 */
	protected function _getClassName($identifier)
	{
		$path      = KInflector::camelize(implode('_', $identifier->path));
        $classname = ucfirst($identifier->package).$path.ucfirst($identifier->name);
		
		return $classname;
	}
	
	/**
	 * Get the base path of the object
	 *
	 * @return string
	 */
	protected function _getFilePath($identifier)
	{
		// Admin is an alias for administrator
		$app  = ($identifier->application == 'admin') ? 'administrator' : $identifier->application;
		$path = JApplicationHelper::getClientInfo($app, true)->path.DS.'components'.DS.'com_'.$identifier->package;

		if(!empty($identifier->name))
		{
			if(count($identifier->path))
			{
				foreach($identifier->path as $sub) {
					$path .= DS.KInflector::pluralize($sub);
				}
			}
			
			if(isset($identifier->path[0]) && $identifier->path[0] == 'view') {
				$path .= DS.$identifier->name;
			}
		}
		
		return $path;
	}

	/**
	 * Get the filename
	 *
	 * @return string The file name for the class
	 */
	protected function _getFileName($identifier)
	{
		$filename = strtolower($identifier->name).'.php';
		
		if(isset($identifier->path[0]) && $identifier->path[0] == 'view') 
		{
			$type     = KFactory::get('lib.koowa.document')->getType();
			$filename = $type.'.php';
		}

		return $filename;
	}
}