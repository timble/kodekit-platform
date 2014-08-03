<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Object Config Serializable Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Object
 */
interface ObjectConfigSerializable
{
    /**
     * Read from a string and create a ObjectConfig object
     *
     * @param  string $string
     * @throws \DomainException
     * @return ObjectConfigSerializable
     */
    public function fromString($string);

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
     * @throws \RuntimeException
     * @return ObjectConfigSerializable
     */
    public function fromFile($filename);

    /**
     * Write a config object to a file.
     *
     * @param  string  $filename
     * @return void
     */
    public function toFile($filename);
}