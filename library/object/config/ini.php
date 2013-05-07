<?php
/**
 * @package		Koowa_Config
 * @subpackage  Format
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * ObjectConfig Format Ini
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Config
 * @subpackage  Format
 */
class ObjectConfigIni extends ObjectConfigFormat
{
    /**
     * Read from a string and create an array
     *
     * @param  string $string
     * @return ObjectConfigIni|false   Returns a ObjectConfig object. False on failure.
     * @throws \RuntimeException
     */
    public static function fromString($string)
    {
        $data = array();

        if(!empty($string))
        {
            $data = parse_ini_string($string, true);

            if($data === false) {
                throw new \RuntimeException('Cannot parse INI string');
            }
        }

        $config = new static($data);

        return $config;
    }

    /**
     * Write a config object to a string.
     *
     * There is no way to have ini values nested further than two levels deep.  Therefore we will only go through the
     * first two levels of the object.
     *
     * @param  ObjectConfig $config
     * @return string|false   Returns a INI encoded string on success. False on failure.
     */
    public function toString()
    {
        $local  = array();
        $global = array();

        $data = (object) $this->toArray();

        // Iterate over the object to set the properties.
        foreach (get_object_vars($data) as $key => $value)
        {
            // If the value is an object then we need to put it in a local section.
            if (is_object($value))
            {
                // Add the section line.
                $local[] = '';
                $local[] = '[' . $key . ']';

                // Add the properties for this section.
                foreach (get_object_vars($value) as $k => $v) {
                    $local[] = $k . '=' . self::_encodeValue($v);
                }
            }
            else
            {
                // Not in a section so add the property to the global array.
                $global[] = $key . '=' . self::_encodeValue($value);
            }
        }

        return implode("\n", array_merge($global, $local));
    }

    /**
     * Encode a value for INI.
     *
     * @param  mixed $value
     * @return string
     */
    protected static function _encodeValue($value)
    {
        $string = '';

        switch (gettype($value))
        {
            case 'integer':
            case 'double':
                $string = $value;
                break;

            case 'boolean':
                $string = $value ? 'true' : 'false';
                break;

            case 'string':
                // Sanitize any CRLF characters..
                $string = '"' . str_replace(array("\r\n", "\n"), '\\n', $value) . '"';
                break;
        }

        return $string;
    }


}