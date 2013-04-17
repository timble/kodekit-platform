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
 * Identifier Filter
 *
 * Validates identifiers in the form of [application::]type.package.[.path].name
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Filter
 */
class FilterIdentifier extends FilterAbstract implements FilterTraversable
{
	/**
	 * Validate a value
	 *
     * @param   scalar  $value Value to be validated
     * @return  bool    True when the variable is valid
     */
    public function validate($value)
    {
        $value = trim($value);
        $pattern = '#^[a-z0-9:\._]+$#';
        return (is_string($value) && preg_match($pattern, $value) == 1);
    }

	/**
     * Sanitize a value
     *
     * @param   scalar  $value Value to be sanitized
     * @return  string
     */
    public function sanitize($value)
    {
        $value = trim($value);
        $pattern = '#[^a-z0-9:\._]$#';
        return preg_replace($pattern, '', $value);
    }
}