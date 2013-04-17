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
 * Path Filter
 *
 * Filters Windows and Unix style file paths
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Filter
 */
class FilterPath extends FilterAbstract implements FilterTraversable
{
	const PATTERN = '#^(?:[a-z]:/|~*/)[a-z0-9_\.-\s/~]*$#i';

    /**
     * Validate a value
     *
     * @param   scalar  $value Value to be validated
     * @return  bool    True when the variable is valid
     */
    public function validate($value)
    {
        $value = trim(str_replace('\\', '/', $value));
        return (is_string($value) && (preg_match(self::PATTERN, $value)) == 1);
    }

    /**
     * Sanitize a value
     *
     * @param   scalar  $value Value to be sanitized
     * @return  string
     */
    public function sanitize($value)
    {
        $value = trim(str_replace('\\', '/', $value));
        preg_match(self::PATTERN, $value, $matches);
        $match = isset($matches[0]) ? $matches[0] : '';

        return $match;
    }
}