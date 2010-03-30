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
 * Boolean filter
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
class KFilterBoolean extends KFilterAbstract
{
	/**
	 * Validate a value
	 * 
	 *  Returns TRUE for boolean values: "1", "true", "on" and "yes", "0", 
	 * "false", "off", "no", and "". Returns FALSE for all non-boolean values. 
	 *
	 * @param	scalar	Value to be validated
	 * @return	bool	True when the variable is valid
	 */
	protected function _validate($value)
	{
		return (null !== filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) );
	}
	
	/**
	 * Sanitize a value
	 * 
	 * Returns TRUE for "1", "true", "on" and "yes". Returns FALSE for all other values. 
	 *
	 * @param	scalar	Value to be sanitized
	 * @return	bool
	 */
	protected function _sanitize($value)
	{
		return filter_var($value, FILTER_VALIDATE_BOOLEAN);
	}
}