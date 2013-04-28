<?php
/**
 * @package     Koowa_Database
 * @subpackage  Query
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Database Query Parameters
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Database
 * @subpackage  Query
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