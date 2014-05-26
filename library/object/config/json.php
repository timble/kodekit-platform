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
 * Object Config Json
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
 */
class ObjectConfigJson extends ObjectConfigFormat
{
    /**
     * Read from a string and create an array
     *
     * @param  string $string
     * @return $this
     * @throws \DomainException  If the JSON cannot be decoded or if the encoded data is deeper than the recursion limit.
     */
    public function fromString($string)
    {
        $data = array();

        if(!empty($string))
        {
            $data = json_decode($string, true);

            if($data === null) {
                throw new \DomainException('Cannot decode data from JSON string');
            }
        }

        $this->add($data);

        return $this;
    }

    /**
     * Write a config object to a string.
     *
     * @return string|false    Returns a JSON encoded string on success. False on failure.
     * @throws \DomainException Object could not be encoded to valid JSON.
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