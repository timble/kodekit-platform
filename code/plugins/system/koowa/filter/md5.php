<?php
/**
* @version      $Id:koowa.php 251 2008-06-14 10:06:53Z mjaz $
* @category		Koowa
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.koowa.org
*/

/**
 * MD5 filter
 *
 * Validates or sanitizes an md5 hash (32 chars [a-f0-9])
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
class KFilterMd5 extends KObject implements KFilterInterface
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
	   	$pattern = '/^[a-f0-9]{32}$/';
    	return (is_string($var) && preg_match($pattern, $var) == 1);
	}
	
	/**
	 * Sanitize a variable
	 *
	 * @param	mixed	Variable to be sanitized
	 * @return	string
	 */
	public function sanitize($var)
	{
		$var 		= trim(strtolower($var));
		$pattern 	= '/[^a-f0-9]*/';
    	return substr(preg_replace($pattern, '', $var), 0, 32);
	}
}