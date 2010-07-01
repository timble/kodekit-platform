<?php
/**
* @version		$Id$
* @category		Koowa
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link         http://www.koowa.org
*/

/**
 * Koowa constant, if true koowa is loaded
 */
define('KOOWA', 1);

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
     * 
     * @var string
     */
    const VERSION = '0.7.0';
    
    /**
     * Path to Koowa libraries
     */
    protected static $_path;

    /**
     * Get the version of the Koowa library
     */
    public static function getVersion()
    {
   	    return self::VERSION;
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
}