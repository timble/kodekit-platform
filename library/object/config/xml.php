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
 * Object Config Xml
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
 */
class ObjectConfigXml extends ObjectConfigFormat
{
    /**
     * Read from a string and create an array
     *
     * @param  string $string
     * @return ObjectConfigXml|false   Returns a ObjectConfig object. False on failure.
     * @throws \RuntimeException
     */
    public static function fromString($string)
    {
        $data = array();

        if(!empty($string))
        {
            $xml  = simplexml_load_string($string);
            foreach ($xml->children() as $node) {
                $data[(string) $node['name']] = self::_decodeValue($node);
            }
        }

        $config = new static($data);

        return $config;
    }

    /**
     * Write a config object to a string.
     *
     * @param  ObjectConfig $config
     * @return string|false   Returns a XML encoded string on success. False on failure.
     */
    public function toString()
    {
        $addChildren = function($value, $key, $node)
        {
            if (is_scalar($value))
            {
                $n = $node->addChild('option', $value);
                $n->addAttribute('name', $key);
                $n->addAttribute('type', gettype($value));
             }
             else
             {
                $n = $node->addChild('config');
                $n->addAttribute('name', $key);
                $n->addAttribute('type', gettype($value));

                 array_walk($value, $addChildren, $n);
            }
        };

        $xml  = simplexml_load_string('<config />');
        $data = $this->toArray();
        array_walk($data, $addChildren, $xml);

        return $xml->asXML();
    }

    /**
     * Method to get a PHP native value for a SimpleXMLElement object
     *
     * @param   object  $node  SimpleXMLElement object for which to get the native value.
     * @return  mixed  Native value of the SimpleXMLElement object.
     */
    protected static function _decodeValue($node)
    {
        switch ($node['type'])
        {
            case 'integer':
                $value = (string) $node;
                return (int) $value;
                break;

            case 'string':
                return (string) $node;
                break;

            case 'boolean':
                $value = (string) $node;
                return (bool) $value;
                break;

            case 'double':
                $value = (string) $node;
                return (float) $value;
                break;

            case 'array':
            default     :

                $value = array();
                foreach ($node->children() as $child) {
                    $value[(string) $child['name']] = self::_decodeValue($child);
                }

                break;
        }

        return $value;
    }
}