<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Object  Locator Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
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