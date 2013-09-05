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
 * Ini Filter
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Filter
 */
class FilterIni extends FilterAbstract
{
    /**
     * Validate a value
     *
     * @param   scalar  $value Value to be validated
     * @return   bool   True when the variable is valid
     */
    public function validate($value)
    {
        try {
            $config = ObjectConfigIni::fromString($value);
        } catch(\RuntimeException $e) {
            $config = null;
        }
        return is_string($value) && !is_null($config);
    }

    /**
     * Sanitize a value
     *
     * @param   scalar  $value Value to be sanitized
     * @return  ObjectConfig
     */
    public function sanitize($value)
    {
        if(!$value instanceof ObjectConfig)
        {
            if(is_string($value)) {
                $value = ObjectConfigIni::fromString($value);
            } else {
                $value = new ObjectConfigIni($value);
            }
        }

        return $value;
    }
}