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
 * Integer filter
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
class KFilterInt extends KFilterAbstract
{
	/**
	 * Validate a value
	 *
	 * @param	scalar	Value to be validated
	 * @return	bool	True when the variable is valid
	 */
	protected function _validate($value)
	{
		return empty($value) || (false !== filter_var($value, FILTER_VALIDATE_INT));
	}
	
	/**
	 * Sanitize a value
	 *
	 * @param	scalar	Value to be sanitized
	 * @return	int
	 */
	protected function _sanitize($value)
	{
		return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
	}
}

