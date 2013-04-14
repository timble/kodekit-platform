<?php
/**
 * @package      Koowa_Filter
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link 		http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Recursive Filter
 *
 * If the data passed is an array or is traversable the filter will recurse over it and filter each individual value.
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Filter
 */
abstract class FilterRecursive extends FilterAbstract
{
    /**
     * Validate a scalar or traversable data
     *
     * NOTE: This should always be a simple yes/no question (is $data valid?), so only true or false should be returned
     *
     * @param   mixed   $data Value to be validated
     * @return  bool    True when the data is valid. False otherwise.
     */
    public function validate($data)
    {
        if(is_array($data) || $data instanceof Traversable)
        {
            foreach((array)$data as $value)
            {
                if($this->validate($value) ===  false) {
                    $result = false;
                }
            }
        }
        else $result = parent::validate($data);

        return $result;
    }

    /**
     * Sanitize a scalar or traversable data
     *
     * @param   mixed   $data Value to be sanitized
     * @return  mixed   The sanitized value
     */
    public function sanitize($data)
    {
        if(is_array($data) || $data instanceof Traversable)
        {
            foreach((array)$data as $key => $value)
            {
                if(is_array($data)) {
                    $data[$key] = $this->sanitize($value);
                } else {
                    $data->$key = $this->sanitize($value);
                }
            }
        }
        else  $data = parent::sanitize($data);

        return $data;
    }
}