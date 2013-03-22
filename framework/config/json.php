<?php
/**
 * @package		Koowa_Config
 * @subpackage  Format
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Config Format Json
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Config
 * @subpackage  Format
 */
class ConfigJson extends ConfigFormat
{
    /**
     * Read from a string and create an array
     *
     * @param  string $string
     * @return ConfigJson|false   Returns a Config object. False on failure.
     * @throws \RuntimeException
     */
    public static function fromString($string)
    {
        $data = array();

        if(!empty($string))
        {
            $data = json_decode($string, true);

            if($data === null) {
                throw new \RuntimeException('Cannot decode JSON string');
            }
        }

        $config = new static($data);

        return $config;
    }

    /**
     * Write a config object to a string.
     *
     * @param  Config $config
     * @return string|false     Returns a JSON encoded string on success. False on failure.
     */
    public function toString()
    {
        $data = $this->toArray();

        return json_encode($data);
    }
}