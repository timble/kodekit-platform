<?php
/**
 * @package		Koowa_Object
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Object Hashable interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Object
 */
interface ObjectHandlable
{
	/**
	 * Get the object handle
	 *
	 * This function returns an unique identifier for the object. This id can be used as a hash key for storing objects
     * or for identifying an object
	 *
	 * @return string A string that is unique, or NULL
	 */
	public function getHandle();
}