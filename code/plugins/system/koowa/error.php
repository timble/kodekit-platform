<?php
/**
 * @version     $Id$
 * @package     Koowa_Exception
 * @subpackage	Error
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Koowa Error class
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @package     Koowa_Exception
 * @subpackage	Error
 */
class KError
{
    /**
     * Undefined Error
     */
    const UNDEFINED = 0;

    /**
     * KFactory Error: Missing class
     */
    const FACTORY_CLASS = 1000;

    /**
     * KFactory Error: Missing getInstance() method
     */
    const FACTORY_GETINSTANCE   = 1001;

}