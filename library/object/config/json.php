<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Object Config Json
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Object
 */
class ObjectConfigJson extends ObjectConfigFormat
{
    /**
     * Read from a string and create an array
     *
     * @param  string $string
     * @param  bool    $object  If TRUE return a ConfigObject, if FALSE return an array. Default TRUE.
     * @throws \DomainException  If the JSON cannot be decoded or if the encoded data is deeper than the recursion limit.
     * @return ObjectConfigJson|array
     */
    public function fromString($string, $object = true)
    {
        $data = array();

        if(!empty($string))
        {
            $data = json_decode($string, true);

            if($data === null) {
                throw new \DomainException('Cannot decode data from JSON string');
            }
        }

        return $object ? $this->merge($data) : $data;
    }

    /**
     * Write a config object to a string.
     *
     * @throws \DomainException Object could not be encoded to valid JSON.
     * @return string|false    Returns a JSON encoded string on success. False on failure.
     */
    public function toString()
    {
        $data = $this->toArray();
        $data = json_encode($data);

        if($data === false) {
            throw new \DomainException('Cannot encode data to JSON string');
        }

        return $data;
    }
}