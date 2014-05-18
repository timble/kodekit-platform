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
 * Internal Url Filter
 *
 * Check if an refers to a legal URL inside the system. Use when redirecting to an URL that was passed in a request.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Filter
 */
class FilterInternalurl extends FilterAbstract implements FilterTraversable
{
    /**
     * Validate a value
     *
     * @param   scalar  $value Value to be validated
     * @return  bool    True when the variable is valid
     */
    public function validate($value)
    {
        if(!is_string($value)) {
            return false;
        }

        if(stripos($value, (string)  $this->getObject('request')->getUrl()->toString(HttpUrl::SCHEME | HttpUrl::HOST)) !== 0) {
            return false;
        }

        return true;
    }

    /**
     * Sanitize a value
     *
     * @param   scalar  $value Value to be sanitized
     * @return  string
     */
    public function sanitize($value)
    {
        //TODO : internal url's should not only have path and query information
        return filter_var($value, FILTER_SANITIZE_URL);
    }
}

