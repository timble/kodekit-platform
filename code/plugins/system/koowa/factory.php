<?php
/**
 * @version 	$Id:factory.php 46 2008-03-01 18:39:32Z mjaz $
 * @package		Koowa
 * @copyright	Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * KFactory class
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @package     Koowa
 * @version     1.0
 */
class KFactory
{
	/**
	 * The collection of instances
	 *
	 * @var	array
	 */
	protected static $_instances;

	/**
	 * The alias object map
	 *
	 * @var	array
	 */
	protected static $_objectMap = array(
      	'table'     => 'DatabaseTable',
        'row'       => 'DatabaseRow',
      	'rowset'    => 'DatabaseRowset'
	);

	/**
	 * Get an instance of an instanciatable class based on the class name
	 *
	 * @param string $object
	 * @param string $type
	 *
	 * @return object
	 */
	public static function get($object, $default = 'default')
	{
		$result = array();
		$result['default'] = $default;

		//Parse the client from the object string
		if(strpos($object, ':'))
		{
			$parts = explode(':', $object);
			$result['client'] = $parts[0];
			$object = $parts[1];
		}

		// Get the different parts of the classname
		$parts = KInflector::explode($object);

		//Proxy calls to JFactory (simple solution)
		if(count($parts) == 1)
		{
			//Handle exceptions
			if($object == 'Database') {
				$object = 'DBO';
			}
			if($object == 'Authorization') {
				$object = 'ACL';
			}

			$result['type'] = $object;

			$args = func_get_args();
			unset($args[0]);

			return self::__call('get'.ucfirst($result['type']), $args);
		}

		if(isset($parts[0])) {
			$result['component'] = $parts[0];
		}

		if(isset($parts[1])) {
			$result['type'] = $parts[1];
		}

		if(isset($parts[2])) {
			$result['name'] = $parts[2];
		}

		return self::getInstance($result);
	}

	/**
	 * Get an instance of an instanciatable class
	 *
	 * Individual class getInstance methods should never implement a factory pattern.
	 *
	 * @param 	array	Object information
	 * @param 	array	Object options
	 * @throws	KException
	 * @return object
	 */
	public static function getInstance($object, $options = array())
	{
		if(array_key_exists('client', $object)) {
			$client = $object['client'];
		} else {
			$client = JFactory::getApplication()->getName();
		}

		if(array_key_exists('component', $object)) {
			$component = $object['component'];
		} else {
			$component = '';
		}

		if(array_key_exists('type', $object)) {
			$type =  $object['type'];
		} else {
			$type = '';
		}

		if(array_key_exists('name', $object)) {
			$name = $object['name'];
		} else {
			$name = '';
		}

		if(array_key_exists('default', $object)) {
			$default = $object['default'];
		} else {
			$default = 'default';
		}

		if(array_key_exists($object['type'], self::$_objectMap)) {
			$base =  self::$_objectMap[$object['type']];
		} else {
			$base = $object['type'];
		}

		$signature = md5($client.$component.$type.$name);
		if(!isset(self::$_instances[$signature]))
		{
            $classNameInstance = ucfirst($component).ucfirst($type).ucfirst($name);
			$classNameDefault  = 'K'.ucfirst($base).ucfirst($default);

			if(!class_exists($classNameDefault)) {
				throw new KException("Class '$classNameDefault' doesn't exist.", KError::FACTORY_CLASS);
			}

			if (!class_exists( $classNameInstance ))
			{
				//Create path
				if(!isset($options['base_path']))
				{
					$options['base_path']  = JApplicationHelper::getClientInfo($client, true)->path;
					$options['base_path'] .= DS.'components'.DS.'com_'.$component;

					if(!empty($name)) {
						$options['base_path'] .= DS.KInflector::pluralize($type);
					}
				}

				//Find the file
				Koowa::import('joomla.filesystem.path');
				if($file = JPath::find($options['base_path'], self::getFileName($classNameDefault, $name)))
				{
					require_once $file;
					if (!class_exists( $classNameInstance )) {
						throw new KException($classNameInstance.' not found in file.' );
					}

					//Set the view base_path in the options array
					$options['base_path'] = dirname($file);
				}
				else $classNameInstance = $classNameDefault;
			}

			//Set the name in the options array
			$options['name'] = array('prefix' => $component, 'base' => $base, 'suffix' => $name);
			
			// Create the object
			self::$_instances[$signature] = new $classNameInstance($options);

		}

		return self::$_instances[$signature];
	}

	/**
	 * Get the filename for a specific class
	 *
	 * Function checks to see if the class has a static getFileName function,
	 * otherwise it returns a default name.
	 *
	 * return string The file name for the class
	 */
	public static function getFileName($class, $name)
	{
		//Create the filename
		if(method_exists($class, 'getFileName')) {
			$filename = call_user_func( array($class, 'getFileName'), array( 'name' => $name));
		} else {
			$filename = strtolower($name).'.php';
		}

		return $filename;
	}

	/**
	 * Overloaded call function
	 *
	 * @param  string $function		The function name
	 * @param  array  $arguments	The function arguments
	 * @return mixed The result of the function
	 */
	public static function __call($function, $arguments)
	{
		return call_user_func_array(array('JFactory', $function), $arguments);
	}
}