<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Language filter
 *
 * Filters ISO codes like en-GB (lang-COUNTRY). Only checks the format, it doesn't care whether the language or country
 * actually exist
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Filter
 */
class FilterLang extends FilterAbstract implements FilterTraversable
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
        $pattern = '/^[a-z]{2}-[A-Z]{2}$/';
        return (empty($value))
                || (is_string($value) && preg_match($pattern, $value) == 1);
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

        $parts  = explode('-', $value, 2);
        if(2 != count($parts)) {
            return null;
        }

        $parts[0]   = substr(preg_replace('/[^a-z]*/', '', $parts[0]), 0, 2);
        $parts[1]   = substr(preg_replace('/[^A-Z]*/', '', $parts[1]), 0, 2);
        $result = implode('-', $parts);

        // just making sure :-)
        if($this->_validate($result)) {
            return $result;
        }

        return null;
    }
}