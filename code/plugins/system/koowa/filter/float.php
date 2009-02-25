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
 * Float filter
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
class KFilterFloat extends KObject implements KFilterInterface
{
	/**
	 * Validate a variable
	 *
	 * @param	mixed	Variable to be validated
	 * @return	bool	True when the variable is valid
	 */
	public function validate($var)
	{
		return (false !== filter_var($var, FILTER_VALIDATE_FLOAT));
	}
	
	/**
	 * Sanitize a variable
	 *
	 * @param	mixed	Variable to be sanitized
	 * @return	float
	 */
	public function sanitize($var)
	{
		return (float) filter_var($var, FILTER_SANITIZE_NUMBER_FLOAT, 
			FILTER_FLAG_ALLOW_FRACTION & FILTER_FLAG_ALLOW_THOUSAND & FILTER_FLAG_ALLOW_SCIENTIFIC);
	}
}

