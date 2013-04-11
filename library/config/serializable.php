<?php
/**
 * @package		Koowa_Confi
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Config Serializable Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Config
 */
interface ConfigSerializable
{
    /**
     * Read from a string and create a Config object
     *
     * @param  string $string
     * @return Config|false   Returns a Config object. False on failure.
     * @throws \RuntimeException
     */
    public static function fromString($string);

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
     * @return ConfigSerializable
     * @throws \RuntimeException
     */
    public static function fromFile($filename);

    /**
     * Write a config object to a file.
     *
     * @param  string  $filename
     * @return void
     */
    public function toFile($filename);
}