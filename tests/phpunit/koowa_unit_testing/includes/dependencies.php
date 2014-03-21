<?php
/**
 * @version     $Id$
 * @package     Koowa_Tests
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Class to determine if dependencies are met
 */
class Dependencies
{
    /**
     * Check Joomla!
     *
     * @return boolean
     */
    public static function checkJoomla()
    {
        $file = JPATH_CONFIGURATION.'/configuration.php';
        return file_exists($file) AND filesize($file) > 10;
    }

    /**
     * Check Koowa
     *
     * @return boolean
     */
    public static function checkKoowa()
    {
        return file_exists(JPATH_KOOWA.'/koowa.php');
    }

}