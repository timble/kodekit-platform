<?php
/**
 * @version     $Id$
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license     GNU GPL <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * beer class
 *
 * Provides constants and metadata for Beer such as version info
 * 
 *  @package	Beer
 */
class Beer
{
    /**
     * Nooku version
     */
    const _VERSION = '0.1.0';

    /**
     * Get the version of Beer
     */
    public static function getVersion()
    {
        return self::_VERSION;
    }
}