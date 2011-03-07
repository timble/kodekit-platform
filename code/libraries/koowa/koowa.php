<?php
/**
* @version		$Id$
* @category		Koowa
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link         http://www.nooku.org
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
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa
 */
class Koowa
{
    /**
     * Koowa version
     * 
     * @var string
     */
    const VERSION = '0.7.0-dev';
    
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