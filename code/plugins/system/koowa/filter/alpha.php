<?php
/**
* @version      $Id$
* @category		Koowa
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.koowa.org
*/

/**
 * Alphabetic filter.
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
class KFilterAlpha extends KObject implements KFilterInterface
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
		
		return ctype_alpha($var);
	}
	
	/**
	 * Sanitize a variable
	 *
	 * @param	mixed	Variable to be sanitized
	 * @return	string
	 */
	public function sanitize($var)
	{
		$pattern 	= '/[^[a-zA-Z]*/';
    	return preg_replace($pattern, '', $var);
	}
}