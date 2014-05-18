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
 * Filter Chain
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Filter
 */
class FilterChain extends FilterAbstract
{
    /**
     * The filter queue
     *
     * @var	ObjectQueue
     */
    protected $_queue;

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
        $priority = $priority == null ? $filter->getPriority() : $priority;
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
}