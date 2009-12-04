<?php
/**
 * @version     $Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license     GNU GPL <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Profiles namespace
 *
 * Provides constants and metadata for profiles namespace such as version info
 * 
 *  @package	Profiles
 */
class comProfiles
{
    /**
     * Wersion
     */
    const _VERSION = '0.1.0';

    /**
     * Get the version
     */
    public static function getVersion()
    {
        return self::_VERSION;
    }
}