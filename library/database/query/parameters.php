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
 * Database Query Parameters
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Database
 */
class DatabaseQueryParameters extends ObjectArray
{
    /**
     * Constructor
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return ObjectArray
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $parameters = ObjectConfig::unbox($config->parameters);
        foreach ($parameters as $key => $values) {
            $this->set($key, $values);
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $object An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'parameters' => array(),
        ));

        parent::_initialize($config);
    }

    /**
     * Get a query parameter
     *
     * @param   string  $name The parameter name.
     * @return  string  The corresponding value.
     */
    public function get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * Set a query parameter by name
     *
     * @param   string  $name  The parameter name
     * @param   mixed   $value The value for the parameter
     * @return  DatabaseQueryParameters
     */
    public function set($name, $value)
    {
        $this->offsetSet($name, $value);
        return $this;
    }

    /**
     * Test existence of a parameter
     *
     * @param  string  $name The parameter name
     * @return boolean
     */
    public function has($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * Unset a key
     *
     * @param   string  $name The parameter name
     * @return  DatabaseQueryParameters
     */
    public function remove($name)
    {
        $this->offsetUnset($name);
        return $this;
    }
}