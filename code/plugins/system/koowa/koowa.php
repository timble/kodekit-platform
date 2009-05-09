<?php
/**
* @version      $Id:koowa.php 251 2008-06-14 10:06:53Z mjaz $
* @category		Koowa
* @package      Koowa
* @copyright    Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
*/

/**
 * Koowa is active
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
     * Get the URL to a Koowa folder
     *
     * @param string	$type	The type of URL to return [root|media|...]
     * @return 	string	URL
     */
    public static function getURL($type)
    {
    	$url = '';
    	
    	switch($type) 
    	{
    		case 'root' :
    			$url = JURI::root(true);
    			break;
    		case 'media' :
    			$url = JURI::root(true).'/media/plg_koowa/';
    			break;
    		case 'css' :
    			$url = JURI::root(true).'/media/plg_koowa/css/';
    			break;
    		case 'images' :
    			$url = JURI::root(true).'/media/plg_koowa/images/';
    			break;
    		case 'js' :
    			$url = JURI::root(true).'/media/plg_koowa/js/';
    			break;	
    		default:
    			throw new KException('No url of type: '.$type);	
    			break;
    	}
    	
    	return $url;
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
}