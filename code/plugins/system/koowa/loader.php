<?php
/**
* @version      $Id:koowa.php 251 2008-06-14 10:06:53Z mjaz $
* @category		Koowa
* @package      Koowa
* @copyright    Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
*/

spl_autoload_register(array('KLoader', 'loadClass'));

/**
 * Koowa class
 *
 * Loads classes and files
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package     Koowa
 */
class KLoader
{
	/**
     * Load the file for a class
     *
     * Is capable of autoloading Koowa library classes based on a camelcased
     * classname that represents the directory structure.
     *
     * @param   string  $class  The class that will be loaded
     * @return  boolean True on success
     */
    public static function loadClass( $class )
    {
    	// pre-empt further searching for the named class or interface.
		// do not use autoload, because this method is registered with
		// spl_autoload already.
		if (class_exists($class, false) || interface_exists($class, false)) {
			return;
		}

		// if class start with a 'K' it is a Koowa framework class.
		// create the path and register it with the loader.
		switch(substr($class, 0, 1))
		{
			case 'K' :
			{
				$word  = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', substr_replace($class, '', 0, 1)));
				$parts = explode('_', $word);
			
				if(count($parts) > 1) {
					$path = str_replace('_', DS, $word);
				} else {
					$path = $word.DS.$word;
				}
				
				if(!is_file(dirname(__FILE__).DS.$path.'.php')) {
					$path = $path.DS.array_pop($parts);	
				}
					
				self::register($class,  dirname(__FILE__).DS.$path.'.php');
				
			} break;
		}

		$classes = self::register();
        if(array_key_exists( strtolower($class), $classes)) {
            include($classes[strtolower($class)]);
            return true;
        }

        return false;
    }

	/**
 	 * Intelligent file importer
	 *
 	 * @param string $path A dot syntax path
 	 */
	public static function loadFile( $path, $basepath = '')
	{
		$parts = explode( '.', $path );

		$result = '';
		switch($parts[0])
		{
			case 'lib' :
			{
				if($parts[1] == 'joomla') 
				{
					unset($parts[0]);
					$path   = implode('.', $parts);
					$result = JLoader::import($path, null, 'libraries.' );
				} 
				
				if($parts[1] == 'koowa') 
				{
					unset($parts[0]);
					unset($parts[1]);
					$path   = implode('.', $parts);
					$result = JLoader::import($path, Koowa::getPath());
				}
				
			} break;
			
			default :
			{
				if(strpos($parts[0], '::') !== false) {
					$app  = explode( '::', $parts[0] );	
					$name =  $app[0];
				} else {
					$app  = KFactory::get('lib.joomla.application');
					$name = $app->getName(); 
				}
				
				$app = ($name == 'site') ? JPATH_SITE : JPATH_ADMINISTRATOR;
				$com = $parts[1];

				unset($parts[0]);
				unset($parts[1]);

				$base   = $app.DS.'components'.DS.'com_'.$com;
				$path   = implode('.', $parts);
					
				$result = JLoader::import($path, $base, $com.'.' );
				
			} break;
		}

		return $result;
	}
	
  	/**
     * Add a class to autoload
     *
     * Proxies the JLoader::register function
     *
     * @param	string $classname	The class name
     * @param	string $file		Full path to the file that holds the class
     * @return	array|boolean  		Array of classes
     * @see JLoader::register
     */
    public static function register ($class = null, $file = null) {
        return JLoader::register($class, $file);
    }
}