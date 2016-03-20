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
 * Filter Iterator
 *
 * If the data passed is an array or is traversable the filter will iterate over it and filter each individual value.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Filter
 */
class FilterIterator extends ObjectDecorator implements FilterInterface, FilterTraversable
{
    /**
     * Validate a scalar or traversable data
     *
     * NOTE: This should always be a simple yes/no question (is $data valid?), so only true or false should be returned
     *
     * @param   mixed   $data Value to be validated
     * @return  bool    True when the data is valid. False otherwise.
     */
    public function validate($data)
    {
        $result = true;

        if(is_array($data) || $data instanceof \Traversable)
        {
            foreach($data as $value)
            {
                if($this->validate($value) ===  false) {
                    $result = false;
                }
            }
        }
        else $result = $this->getDelegate()->validate($data);

        return $result;
    }

    /**
     * Sanitize a scalar or traversable data
     *
     * @param   mixed   $data Value to be sanitized
     * @return  mixed   The sanitized value
     */
    public function sanitize($data)
    {
        if(is_array($data) || $data instanceof \Traversable)
        {
            foreach((array)$data as $key => $value)
            {
                if(is_array($data)) {
                    $data[$key] = $this->sanitize($value);
                } else {
                    $data->$key = $this->sanitize($value);
                }
            }
        }
        else  $data = $this->getDelegate()->sanitize($data);

        return $data;
    }

    /**
     * Resets any generated errors for the filter
     *
     * @return FilterIterator
     */
    public function reset()
    {
        $this->getDelegate()->reset();
        return $this;
    }

    /**
     * Get the priority of the filter
     *
     * @return  integer The priority level
     */
    public function getPriority()
    {
        return $this->getDelegate()->getPriority();
    }

    /**
     * Get a list of error that occurred during sanitize or validate
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->getDelegate()->getErrors();
    }

    /**
     * Add an error message
     *
     * @param string $message The error message
     * @return FilterIterator
     */
    public function addError($message)
    {
        $this->getDelegate()->addError($message);
        return $this;
    }

    /**
     * Set the decorated filter
     *
     * @param   FilterInterface $delegate The decorated filter
     * @return  FilterIterator
     * @throws  \InvalidArgumentException If the delegate is not a filter
     */
    public function setDelegate($delegate)
    {
        if (!$delegate instanceof FilterInterface) {
            throw new \InvalidArgumentException('Filter: '.get_class($delegate).' does not implement FilterInterface');
        }

        return parent::setDelegate($delegate);
    }

    /**
     * Get the decorated filter
     *
     * @return FilterInterface
     */
    public function getDelegate()
    {
        return parent::getDelegate();
    }
}