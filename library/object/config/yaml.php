<?php
/**
 * @package		Koowa_Config
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * ObjectConfig Yaml
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Config
 */
class ObjectConfigYaml extends ObjectConfigFormat
{
    /**
     * Read from a YAML string and create a config object
     *
     * @param  string $string
     * @return ObjectConfigYaml|false   Returns a ObjectConfig object. False on failure.
     * @throws \RuntimeException
     */
    public static function fromString($string)
    {
        $config = false;

        if(function_exist('yaml_parse'))
        {
            $data = array();

            if(!empty($string))
            {
                $data = yaml_parse($string);

                if($data === false) {
                    throw new \RuntimeException('Cannot parse YAML string');
                }
            }

            $config = new static($data);
        }

        return $config;
    }

    /**
     * Write a config object to a YAML string.
     *
     * @param  ObjectConfig $config
     * @return string|false     Returns a YAML encoded string on success. False on failure.
     */
    public function toString()
    {
        $result = false;

        if(function_exists('yaml_emit'))
        {
            $data   = $this->toArray();
            $result = yaml_emit($data, \YAML_UTF8_ENCODING);
        }

        return $result;
    }
}