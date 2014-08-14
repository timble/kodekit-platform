<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Filter Chain
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Filter
 */
/**
 * Filter Chain
 *
 * @author  Johan Janssens <https://github.com/johanjanssens>
 * @package Koowa\Library\Filter
 */
class FilterChain extends Object implements FilterInterface
{
    /**
     * The filter queue
     *
     * @var	ObjectQueue
     */
    protected $_queue;

    /**
     * The last filter
     *
     * @var FilterInterface
     */
    protected $_last;

    /**
     * The filter priority
     *
     * @var integer
     */
    protected $_priority;

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'priority' => self::PRIORITY_NORMAL,
        ));

        parent::_initialize($config);
    }

    /**
     * Constructor.
     *
     * @param ObjectConfig $config	An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Create the queue
        $this->_queue = $this->getObject('lib:object.queue');

        //The filter priority
        $this->_priority = $config->priority;
    }

    /**
     * Validate a scalar or traversable value
     *
     * NOTE: This should always be a simple yes/no question (is $value valid?), so only true or false should be returned
     *
     * @param   mixed   $value Value to be validated
     * @return  bool    True when the value is valid. False otherwise.
     */
    public function validate($value)
    {
        $result = true;

        foreach($this->_queue as $filter)
        {
            if($filter->validate($value) === false) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * Sanitize a scalar or traversable value
     *
     * @param   mixed   $value Value to be sanitized
     * @return  mixed   The sanitized value
     */
    public function sanitize($value)
    {
        foreach($this->_queue as $filter) {
            $value = $filter->sanitize($value);
        }

        return $value;
    }

    /**
     * Add a filter to the queue based on priority
     *
     * @param FilterInterface 	$filter A Filter
     * @param integer	        $priority The command priority, usually between 1 (high priority) and 5 (lowest),
     *                                    default is 3. If no priority is set, the command priority will be used
     *                                    instead.
     *
     * @return FilterChain
     */
    public function addFilter(FilterInterface $filter, $priority = null)
    {
        //Store reference to be used for filter chaining
        $this->_last = $filter;

        //Enqueue the filter
        $this->_queue->enqueue($filter, $priority);
        return $this;
    }

    /**
     * Get a list of error that occurred during sanitize or validate
     *
     * @return array
     */
    public function getErrors()
    {
        $errors = array();
        foreach($this->_queue as $filter) {
            $errors = array_merge($errors, $filter->getErrors());
        }

        return $errors;
    }

    /**
     * Get the priority of the filter
     *
     * @return  integer The priority level
     */
    public function getPriority()
    {
        return $this->_priority;
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
        //Call the method on the filter if it exists
        if($this->_last instanceof FilterInterface)
        {
            $methods = $this->_last->getMethods();

            if(isset($methods[$method]))
            {
                call_user_func_array(array($this->_last, $method), $arguments);
                return $this;
            }
        }

        //Create a new filter based on the method name
        $filter = $this->getObject('filter.factory')->createFilter($method, $arguments);
        $this->addFilter($filter);

        return $this;
    }
}