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
 * Filter Interface
 *
 * Validate or sanitize data
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Filter
 */
interface FilterInterface extends ObjectHandlable
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
    public function validate($value);

    /**
     * Sanitize a scalar or traversable value
     *
     * @param   mixed   $value Value to be sanitized
     * @return  mixed   The sanitized value
     */
    public function sanitize($value);

    /**
     * Resets any generated errors for the filter
     *
     * @return FilterInterface
     */
    public function reset();

    /**
     * Get a list of error that occurred during sanitize or validate
     *
     * @return array
     */
    public function getErrors();

    /**
     * Add an error message
     *
     * @param string $message The error message
     * @return FilterInterface
     */
    public function addError($message);

    /**
     * Get the priority of the filter
     *
     * @return  integer The priority level
     */
    public function getPriority();
}