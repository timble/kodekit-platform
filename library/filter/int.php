<?php
/**
* @category		Koowa
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

namespace Nooku\Library;

/**
 * Integer filter
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Filter
 */
class FilterInt extends FilterAbstract implements FilterTraversable
{
	/**
	 * Validate a value
	 *
     * @param   scalar  $value Value to be validated
	 * @return	bool	True when the variable is valid
	 */
    public function validate($value)
	{
		return empty($value) || (false !== filter_var($value, FILTER_VALIDATE_INT));
	}

	/**
	 * Sanitize a value
	 *
     * @param   scalar  $value Value to be sanitized
	 * @return	int
	 */
    public function sanitize($value)
	{
		return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
	}
}

