<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Object Config Php
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Object
 */
class ObjectConfigPhp extends ObjectConfigFormat
{
    /**
     * Read from a string and create an array
     *
     * @param  string $string
     * @param  bool    $object  If TRUE return a ConfigObject, if FALSE return an array. Default TRUE.
     * @throws \DomainException
     * @return ObjectConfigPhp|array
     */
    public function fromString($string, $object = true)
    {
        $data = array();

        if(!empty($string))
        {
            $data = eval($string);

            if($data === false) {
                throw new \DomainException('Cannot evaluate data from PHP string');
            }
        }

        return $object ? $this->merge($data) : $data;
    }

    /**
     * Write a config object to a string.
     *
     * @return string|false   Returns a parsable string representation of the data.. False on failure.
     */
    public function toString()
    {
        $data = $this->toArray();

        return '<?php return '.var_export($data, true).';';
    }

    /**
     * Read from a file and create a config object
     *
     * @param  string $filename
     * @param  bool    $object  If TRUE return a ConfigObject, if FALSE return an array. Default TRUE.
     * @throws \RuntimeException If file doesn't exist is not readable or cannot be included.
     * @return ObjectConfigPhp|array
     */
    public function fromFile($filename, $object = true)
    {
        if (!is_file($filename) || !is_readable($filename)) {
            throw new \RuntimeException(sprintf("File '%s' doesn't exist or not readable", $filename));
        }

        $data = call_user_func(function(){return require func_get_arg(0);}, $filename);

        return $object ? $this->merge($data) : $data;
    }
}