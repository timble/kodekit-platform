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
 * IP filter
 * 
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
class KFilterIP extends KObject implements KFilterInterface
{
	/**
	 * Validate a variable
	 *
	 * @param	mixed	Variable to be validated
	 * @return	bool	True when the variable is valid
	 */
	public function validate($var)
	{
		$var = trim($var);
		return (false !== filter_var($var, FILTER_VALIDATE_IP));
	}
	
	/**
	 * Sanitize a variable
	 *
	 * @param	mixed	Variable to be sanitized
	 * @return	string
	 */
	public function sanitize($var)
	{
		return filter_var($var, FILTER_SANITIZE_IP);
	}
}