<?php
/**
* @version $Id: loader.php 14401 2010-01-26 14:10:00Z louis $
* @package		Joomla.Framework
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

if(!defined('DS')) {
	define( 'DS', DIRECTORY_SEPARATOR );
}

// Register JLoader::load as an autoload class handler.
spl_autoload_register(array('JLoader','load'));

/**
 * @package		Joomla.Framework
 */
abstract class JLoader
{
	/**
	 * Container for already imported library paths.
	 *
	 * @var    array
	 * @since  0.7
	 */
	protected static $_imported = array();
	
	/**
	 * Container for already imported library paths.
	 *
	 * @var    array
	 * @since  0.7
	 */
	protected static $_classes = array();
	 
	 /**
	 * Loads a class from specified directories.
	 *
	 * @param string $name	The class name to look for ( dot notation ).
	 * @param string $base	Search this directory for the class.
	 * @param string $key	String used as a prefix to denote the full path of the file ( dot notation ).
	 * @return void
	 * @since 1.5
	 */
	public static function import( $filePath, $base = null, $key = 'libraries.' )
	{
		$keyPath = $key ? $key . $filePath : $filePath;

		if (!isset(self::$_imported[$keyPath]))
		{
			if ( ! $base ) {
				$base =  dirname( __FILE__ );
			}

			$parts = explode( '.', $filePath );

			$classname = array_pop( $parts );
			switch($classname)
			{
				case 'helper' :
					$classname = ucfirst(array_pop( $parts )).ucfirst($classname);
					break;

				default :
					$classname = ucfirst($classname);
					break;
			}

			$path  = str_replace( '.', DS, $filePath );

			if (strpos($filePath, 'joomla') === 0)
			{
				/*
				 * If we are loading a joomla class prepend the classname with a
				 * capital J.
				 */
				$classname	= 'J'.$classname;
				$classes	= JLoader::register($classname, $base.DS.$path.'.php');
				$rs			= isset($classes[strtolower($classname)]);
			}
			else
			{
				/*
				 * If it is not in the joomla namespace then we have no idea if
				 * it uses our pattern for class names/files so just include.
				 */
				$rs   = include($base.DS.$path.'.php');
			}

			self::$_imported[$keyPath] = $rs;
		}

		return self::$_imported[$keyPath];
	}

	/**
	 * Add a class to autoload
	 *
	 * @param	string $classname	The class name
	 * @param	string $file		Full path to the file that holds the class
	 * @param   bool   $force  		True to overwrite the autoload path value for the class if it already exists.
	 * @return	array|boolean  		Array of classes
	 * @since 	1.5
	 */
	public static function register ($class = null, $path = null, $force = true)
	{
	    // Sanitize class name.
		$class = strtolower($class);

		// Only attempt to register the class if the name and file exist.
		if (!empty($class) && is_file($path)) {

			// Register the class with the autoloader if not already registered or the force flag is set.
			if (empty(self::$_classes[$class]) || $force) {
				self::$_classes[$class] = $path;
			}
		}

		return self::$_classes;
	}
	
	/**
	 * Load the file for a class
	 *
	 * @access  public
	 * @param   string  $class  The class that will be loaded
	 * @return  boolean True on success
	 * @since   1.5
	 */
	public static function load( $class )
	{
		$class = strtolower($class); //force to lower case

		if (class_exists($class)) {
			  return;
		}

		$classes = JLoader::register();
		if(array_key_exists( strtolower($class), $classes)) 
		{
			include($classes[$class]);
			return true;
		}
		
		return false;
	}
	
	/**
	 * Method to get the list of registered classes and their respective file 
	 * paths for the autoloader.
	 *
	 * @return  array  The array of class => path values for the autoloader.
	 *
	 * @since   0.7
	 */
	public static function getClassList()
	{
		return self::$_classes;
	}
}

/**
 * Global application exit.
 *
 * This function provides a single exit point for the framework.
 *
 * @param mixed Exit code or string. Defaults to zero.
 */
function jexit($message = 0) 
{
    exit($message);
}

/**
 * Intelligent file importer
 *
 * @access public
 * @param string $path A dot syntax path
 * @since 1.5
 */
function jimport( $path ) 
{
	return JLoader::import($path);
}
