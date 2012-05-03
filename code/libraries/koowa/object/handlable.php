<?php
/**
 * @version		$Id$
 * @package		Koowa_Object
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Object Hashable interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Object
 */
interface KObjectHandlable
{
	/**
	 * Get the object handle
	 *
	 * This function returns an unique identifier for the object. This id can be used as
	 * a hash key for storing objects or for identifying an object
	 *
	 * Override this function to implement implement dynamic commands. If you don't want
	 * the command to be enqueued in a chain return NULL instead of a valid handle.
	 *
	 * @return string A string that is unique, or NULL
	 */
	public function getHandle();
}