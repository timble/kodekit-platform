<?php
/**
 * @version 	$Id:factory.php 46 2008-03-01 18:39:32Z mjaz $
 * @category	Koowa
 * @package		Koowa_Factory
 * @subpackage 	Adapter
 * @copyright	Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * KFactoryAdpater for a component
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Factory
 * @subpackage 	Adapter
 */
class KFactoryAdapterComponent extends KFactoryAdapterAbstract
{
	/**
	 * The alias object map
	 *
	 * @var	array
	 */
	protected static $_objectAliasMap = array(
      	'table'     => 'DatabaseTable',
        'row'       => 'DatabaseRow',
      	'rowset'    => 'DatabaseRowset'
	);
	
	/**
	 * Create an instance of a class based on a class identifier
	 *
	 * @param mixed  $string 	The class identifier
	 * @param array  $options 	An optional associative array of configuration settings.
	 * @return object
	 */
	public function createInstance($identifier, array $options)
	{
		$instance = false;
		$array    = array();
		
		$parts = explode('::', $identifier);
			
		$array['application'] = $parts[0];
		
		if(isset($parts[1]) && strpos($parts[1], 'com') !== false) 
		{
			$parts = explode('.', $parts[1]);
			array_shift($parts);
			
			// Admin is an alias for administrator
			$array['application'] = ($array['application'] == 'admin') ? 'administrator' : $array['application'];
			
			// Set the component
			$array['component']	= array_shift($parts);
			
			// Set the type
			$array['type'] 		= array_shift($parts);
			
			// Set the name (last part)
			if(count($parts)) {
				$array['name'] = array_pop($parts);
			}
			
			// Set the path (rest)
			if(count($parts)) {
				$array['path'] = $parts;
			}
				
			$instance = self::_createInstanceFromArray($array, $options);
		}
	
		return $instance;
	}

	/**
	 * Get an instance of an instanciatable class
	 *
	 * @param 	array	Object information
	 * @param 	array	Object options
	 * @throws	KFactoryAdapterException
	 * @return object
	 */
	protected static function _createInstanceFromArray($object, array $options = array())
	{
		$instance = false;
		
		$client    = $object['application'];
		$component = $object['component'];
		
		if(array_key_exists('type', $object)) {
			$type =  $object['type'];
		} else {
			$type = '';
		}
		
		if(array_key_exists('path', $object)) {
			$path =  KInflector::camelize(implode('_', $object['path']));
		} else {
			$path = '';
		}
 
		if(array_key_exists('name', $object)) {
			$name = $object['name'];
		} else {
			$name = '';
		}
 
        $classname = ucfirst($component).ucfirst($type).$path.ucfirst($name);   
      	if (!class_exists( $classname ))
		{
			//Create path
			if(!isset($options['base_path']))
			{
				$options['base_path']  = JApplicationHelper::getClientInfo($client, true)->path;
				$options['base_path'] .= DS.'components'.DS.'com_'.$component;

				if(!empty($name)) 
				{
					$options['base_path'] .= DS.KInflector::pluralize($type);
 
					if(!empty($object['path'])) 
					{
						foreach($object['path'] as $sub) {
							$options['base_path'] .= DS.KInflector::pluralize($sub);
						}
					}
				}
			}

			//Find the file
			Koowa::import('lib.joomla.filesystem.path');
			if($file = JPath::find($options['base_path'], self::_getFileName($type, $name)))
			{
				require_once $file;
				if (!class_exists( $classname )) {
					throw new KFactoryAdapterException($classname.' not found in file.' );
				}

				//Set the view base_path in the options array
				$options['base_path'] = dirname($file);
			}
			else 
			{
				$alias = $type;
				if(array_key_exists($type, self::$_objectAliasMap)) {
					$alias = self::$_objectAliasMap[$type];
				}
				
				if(class_exists( 'K'.ucfirst($alias).$path.ucfirst($name))) {
					$classname = 'K'.ucfirst($alias).$path.ucfirst($name);
				} else {
					$classname = 'K'.ucfirst($alias).$path.'Default';
				}  
			}
		}
		
		if(class_exists( $classname )) 
		{
			$options['name'] = array('prefix' => $component, 'base' => $type.$path, 'suffix' => $name);
			$instance = new $classname($options);
		}

		return $instance;
	}

	/**
	 * Get the filename for a specific class
	 *
	 * Function checks to see if the class has a static getFileName function,
	 * otherwise it returns a default name.
	 *
	 * @return string The file name for the class
	 */
	protected static function _getFileName($class, $name)
	{
		$filename = '';
	
		switch($class)
		{
			case 'view' :
			{
				//Get the document type
				$type   = KFactory::get('lib.joomla.document')->getType();
				$filename = strtolower($name).DS.$type.'.php';
			} break;
			
			default : $filename = strtolower($name).'.php';
		}
		
		return $filename;
	}	
}