<?php
/**
* @version      $Id:koowa.php 251 2008-06-14 10:06:53Z mjaz $
* @category		Koowa
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.koowa.org
*/

/**
 * Internal url filter
 * 
 * Check if an refers to a legal Joomla URL inside the system. Use when 
 * redirecting to an URL that was passed in a request
 *
 * @todo		Do a proper implementation, see NookuFilterEditlink for ideas
 * 
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
class KFilterInternalurl extends KObject implements KFilterInterface
{
	/**
	 * Validate a variable
	 *
	 * @param	mixed	Variable to be validated
	 * @return	bool	True when the variable is valid
	 */
	public function validate($var)
	{
		return is_string($var) && JURI::isInternal($var);
	}
	
	/**
	 * Sanitize a variable
	 *
	 * @param	mixed	Variable to be sanitized
	 * @return	string
	 */
	public function sanitize($var)
	{
		//TODO : internal url's should not only have path and query information
		return filter_var($var, FILTER_SANITIZE_URL);
	}
}

