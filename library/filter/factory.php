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
 * Filter Factory
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Filter
 */
class FilterFactory extends Object implements ObjectSingleton
{
    /**
     * Factory method for KFilterChain classes.
     *
     * Method accepts an array of filter names, or filter object identifiers and will create a chained filter
     * using a FIFO approach.
     *
     * @param	string|array $identifier Filter identifier(s)
     * @param 	object|array $config     An optional ObjectConfig object with configuration options
     * @return  FilterInterface
     */
    public function createChain($identifier, $config = array())
    {
        //Get the filter(s) we need to create
        $filters = (array) $identifier;
        $chain   = $this->getObject('lib:filter.chain');

        foreach($filters as $name)
        {
            $instance = $this->createFilter($name, $config);
            $chain->addFilter($instance);
        }

        return $chain;
    }

    /**
     * Factory method for KFilter classes.
     *
     * If the filter is not an identifier this function will create it directly instead of going through the KObject
     * identification process.
     *
     * @param 	string	$filter Filter identifier
     * @param 	object|array $config     An optional ObjectConfig object with configuration options
     * @throws	\UnexpectedValueException	When the filter does not implement FilterInterface
     * @return  FilterInterface
     */
    public function createFilter($filter, $config = array())
    {
        if(is_string($filter) && strpos($filter, '.') === false )
        {
            $identifier = $this->getIdentifier()->toArray();
            $identifier['name'] = $filter;
        }
        else $identifier = $filter;

        $filter = $this->getObject($identifier, $config);

        //Check the filter interface
        if(!($filter instanceof FilterInterface)) {
            throw new \UnexpectedValueException('Filter:'.get_class($filter).' does not implement FilterInterface');
        }

        return $filter;
    }

    /**
     * Allow for filter chaining
     *
     * @param  string   $method    The function name
     * @param  array    $arguments The function arguments
     * @return mixed The result of the function
     */
    public function __call($method, $arguments)
    {
        return $this->createChain($method, $arguments);
    }
}