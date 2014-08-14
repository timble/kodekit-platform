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
 * Filter Iterator
 *
 * If the data passed is an array or is traversable the filter will iterate over it and filter each individual value.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Filter
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
     * Set the decorated object
     *
     * @return FilterInterface
     */
    public function getDelegate()
    {
        return parent::getDelegate();
    }
}