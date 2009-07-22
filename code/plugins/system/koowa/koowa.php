<?php
/**
* @version		$Id$
* @category		Koowa
* @package      Koowa
* @copyright    Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPL <http://www.gnu.org/licenses/gpl.html>
*/

/**
 * Koowa constant, if true koowa is loaded
 */
define('KOOWA', 1);

/**
 * DS is a shortcut for DIRECTORY_SEPARATOR
 */
if(!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

/**
 * Koowa class
 *
 * Loads classes and files, and provides metadata for Koowa such as version info
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @author		Mathias Verraes <mathias@koowa.org>
 * @package     Koowa
 */
class Koowa
{
    /**
     * Koowa version
     */
    const _VERSION = '0.7.0';

    /**
     * Path to Koowa libraries
     */
    protected static $_path;

    /**
     * Get the version of the Koowa library
     */
    public static function getVersion()
    {
   	    return self::_VERSION;
    }

    /**
     * Get path to Koowa libraries
     */
    public static function getPath()
    {
    	if(!isset(self::$_path)) {
        	self::$_path = dirname(__FILE__);
        }

        return self::$_path;
    }
    
	/**
 	 * Intelligent file importer
	 *
 	 * @param string $path A dot syntax path
 	 */
	public static function import( $path, $basepath = '')
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
			
			case 'plg' :
			{
				unset($parts[0]);
				$path   = implode('.', $parts);
				$result = JLoader::import($path, JPATH_PLUGINS, 'plugins.' );
			} break;
			
			default :
			{
				if(strpos($parts[0], '::') !== false) {
					$app  = explode( '::', $parts[0] );	
					$name =  $app[0];
				} else {
					$app  = KFactory::get('lib.koowa.application');
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
}