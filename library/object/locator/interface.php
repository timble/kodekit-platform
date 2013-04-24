<?php
/**
 * @package		Koowa_Object
 * @subpackage 	Locator
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Library;

/**
 * Object  Locator Interface
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 * @subpackage 	Locator
 */
interface ObjectLocatorInterface
{
    /**
     * Get the locator type
     *
     * @return string
     */
    public function getType();

    /**
     * Get the locator fallbacks
     *
     * @return array
     */
    public function getFallbacks();

    /**
     * Returns a fully qualified class name for a given identifier.
     *
     * @param ObjectIdentifier $identifier An identifier object
     * @return string|false  Return the class name on success, returns FALSE on failure
     */
	public function locate(ObjectIdentifier $identifier);
}