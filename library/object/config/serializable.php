<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Object Config Serializable Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Object
 */
interface ObjectConfigSerializable
{
    /**
     * Read from a string and create a ObjectConfig object
     *
     * @param  string $string
     * @param  bool    $object  If TRUE return a ConfigObject, if FALSE return an array. Default TRUE.
     * @throws \DomainException
     * @return ObjectConfigSerializable|array
     */
    public function fromString($string, $object = false);

    /**
     * Write a config object to a string.
     *
     * @return string
     */
    public function toString();

    /**
     * Read from a file and create an array
     *
     * @param  string $filename
     * @param  bool    $object  If TRUE return a ConfigObject, if FALSE return an array. Default TRUE.
     * @throws \RuntimeException
     * @return ObjectConfigSerializable|array
     */
    public function fromFile($filename, $object = false);

    /**
     * Write a config object to a file.
     *
     * @param  string  $filename
     * @return  ObjectConfigSerializable
     */
    public function toFile($filename);
}