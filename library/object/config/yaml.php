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
 * Object Config Yaml
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
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