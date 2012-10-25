<?php
/**
 * @version     $Id: response.php 4675 2012-06-03 01:05:49Z johanjanssens $
 * @package     Koowa_Http
 * @subpackage  Messsage
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Http Message Headers Class
 *
 * Container class that handles the aggregations of HTTP headers as a collection
 *
 * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec4.html#sec4.2
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Http
 * @subpackage  Messsage
 */
class KHttpMessageHeaders extends KObjectArray
{
    /**
     * Constructor
     *
     * @param KConfig|null $config  An optional KConfig object with configuration options
     * @return \KObjectArray
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $headers = KConfig::unbox($config->headers);
        foreach ($headers as $key => $values) {
            $this->set($key, $values);
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   KConfig $object An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'headers' => array(),
        ));

        parent::_initialize($config);
    }

    /**
     * Returns a header value by name.
     *
     * @param string  $key      The header name
     * @param mixed   $default  The default value
     * @param Boolean $first    Whether to return the first value or all header values
     * @return string|array     The first header value if $first is true, an array of values otherwise
     */
    public function get($key, $default = null, $first = true)
    {
        $key = strtr(strtolower($key), '_', '-');

        if (!isset($this[$key]))
        {
            if (null === $default) {
                return $first ? null : array();
            }

            return $first ? $default : array($default);
        }

        if ($first) {
            return count($this->_data[$key]) ? $this->_data[$key][0] : $default;
        }

        return $this->_data[$key];
    }

    /**
     * Sets a header by name.
     *
     * @param string       $key     The key
     * @param string|array $values  The value or an array of values
     * @param Boolean      $replace Whether to replace the actual value of not (true by default)
     */
    public function set($key, $values, $replace = true)
    {
        $key = strtr(strtolower($key), '_', '-');

        if (true === $replace || !isset($this[$key])) {
            $this->_data[$key] = (array) $values;
        } else {
            $this->_data[$key] = array_merge($this->_data[$key], $values);
        }
    }

    /**
     * Returns true if the HTTP header is defined.
     *
     * @param string $key The HTTP header
     * @return Boolean true if the parameter exists, false otherwise
     */
    public function has($key)
    {
        return array_key_exists(strtr(strtolower($key), '_', '-'), $this->_data);
    }

    /**
     * Returns true if the given HTTP header contains the given value.
     *
     * @param string $key   The HTTP header name
     * @param string $value The HTTP value
     * @return Boolean true if the value is contained in the header, false otherwise
     */
    public function contains($key, $value)
    {
        return in_array($value, $this->get($key, null, false));
    }

    /**
     * Removes a header.
     *
     * @param string $key The HTTP header name
     */
    public function remove($key)
    {
        $key = strtr(strtolower($key), '_', '-');
        unset($this->_data[$key]);
    }

    /**
     * Get a value by key
     *
     * @param   string  $key The key name.
     * @return  string  The corresponding value.
     */
    public function __get($key)
    {
        $key = strtr(strtolower($key), '_', '-');

        $result = null;
        if (isset($this->_data[$key])) {
            $result = $this->_data[$key];
        }

        return $result;
    }

    /**
     * Set a value by key
     *
     * @param   string  $key   The key name
     * @param   mixed   $value The value for the key
     * @return  void
     */
    public function __set($key, $value)
    {
        $key = strtr(strtolower($key), '_', '-');

        $this->_data[$key] = $value;
    }

    /**
     * Test existence of a key
     *
     * @param  string  $key The key name
     * @return boolean
     */
    public function __isset($key)
    {
        $key = strtr(strtolower($key), '_', '-');

        return array_key_exists($key, $this->_data);
    }

    /**
     * Unset a key
     *
     * @param   string  $key The key name
     * @return  void
     */
    public function __unset($key)
    {
        $key = strtr(strtolower($key), '_', '-');

        unset($this->_data[$key]);
    }

    /**
     * Returns the headers as a string.
     *
     * @return string The headers
     */
    public function __toString()
    {
        $headers = $this->_data;
        $content = '';

        ksort($headers);

        foreach ($headers as $name => $values)
        {
            $name    = implode('-', array_map('ucfirst', explode('-', $name)));
            $results = array();

            foreach ($values as $key => $value)
            {
                if(is_numeric($key)) {
                    $results[] = $value;
                } else {
                    $results[] = $key.'='.$value;
                }

                $value = implode($results, '; ');
            }

            $content .= sprintf("%s %s\r\n", $name.':', $value);
        }


        return $content;
    }
}