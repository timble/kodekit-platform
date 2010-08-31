<?php
/**
* @version		$Id$
* @category		Koowa
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.koowa.org
*/

/**
 * Numeric filter
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
class KFilterNumeric extends KFilterAbstract
{
	/** 
	 * Validate whether the given variable is numeric. Numeric strings consist of optional sign, any 
 	 * number of digits, optional decimal part and optional exponential part. Thus +0123.45e6 is a 
 	 * valid numeric value. Hexadecimal notation (0xFF) is allowed too but only without sign, decimal 
 	 * and exponential part
	 *
	 * @param	scalar	Value to be validated
	 * @return	bool	True when the variable is valid
	 */
	protected function _validate($value)
	{
		return (is_string($value) && is_numeric($value));
	}
	
	/**
	 * Sanitize non-numeric characters from the value.
	 *
	 * @param	scalar	Value to be sanitized
	 * @return	float
	 */
	protected function _sanitize($value)
	{
		return (string) filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, 
			FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND | FILTER_FLAG_ALLOW_SCIENTIFIC);
	}
}

