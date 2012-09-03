<?php
/**
* @version		$Id$
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link         http://www.nooku.org
*/

/**
 * APC 3.1.4 compatibility
 */
if(extension_loaded('apc') && !function_exists('apc_exists'))
{
    /**
     * Check if an APC key exists
     *
     * @param  mixed  A string, or an array of strings, that contain keys.
     * @return boolean Returns TRUE if the key exists, otherwise FALSE
     */
    function apc_exists($keys)
    {
		$r;
		apc_fetch($keys,$r);
		return $r;
    }
}