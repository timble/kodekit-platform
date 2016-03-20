<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Object Handlable interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Object
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