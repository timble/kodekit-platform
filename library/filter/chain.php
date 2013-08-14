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
class FilterChain extends ObjectQueue implements FilterInterface
{
    /**
     * Priority levels
     */
    const PRIORITY_HIGHEST = 1;
    const PRIORITY_HIGH    = 2;
    const PRIORITY_NORMAL  = 3;
    const PRIORITY_LOW     = 4;
    const PRIORITY_LOWEST  = 5;

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

        foreach($this as $filter)
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
        foreach($this as $filter) {
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
        $this->enqueue($filter, $priority);
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
        foreach($this as $filter) {
            $errors = array_merge($errors, $filter->getErrors());
        }

        return $errors;
    }

    /**
     * Attach a filter to the queue
     *
     * The priority parameter can be used to override the filter priority while enqueueing the filter.
     *
     * @param   FilterInterface  $filter
     * @param   integer          $priority The filter priority, usually between 1 (high priority) and 5 (lowest),
     *                                     default is 3. If no priority is set, the filter priority will be used
     *                                     instead.
     * @return FilterChain
     * @throws \InvalidArgumentException if the object doesn't implement FilterInterface
     */
    public function enqueue(ObjectHandlable $filter, $priority = null)
    {
        if (!$filter instanceof FilterInterface) {
            throw new \InvalidArgumentException('Filter needs to implement FilterInterface');
        }

        $priority = is_int($priority) ? $priority : FilterChain::PRIORITY_NORMAL;
        return parent::enqueue($filter, $priority);
    }

    /**
     * Removes a filter from the queue
     *
     * @param   FilterInterface   $filter
     * @return  boolean    TRUE on success FALSE on failure
     * @throws \InvalidArgumentException if the object doesn't implement FilterInterface
     */
    public function dequeue(ObjectHandlable $filter)
    {
        if (!$filter instanceof FilterInterface) {
            throw new \InvalidArgumentException('Filter needs to implement FilterInterface');
        }

        return parent::dequeue($filter);
    }

    /**
     * Check if the queue does contain a given filter
     *
     * @param   FilterInterface   $filter
     * @return bool
     * @throws \InvalidArgumentException if the object doesn't implement FilterInterface
     */
    public function contains(ObjectHandlable $filter)
    {
        if (!$filter instanceof FilterInterface) {
            throw new \InvalidArgumentException('Filter needs to implement FilterInterface');
        }

        return parent::contains($filter);
    }
}