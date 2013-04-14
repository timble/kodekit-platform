<?php
/**
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link 		http://www.nooku.org
*/

namespace Nooku\Library;

/**
 * Filter interface
 *
 * Validate or sanitize data
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Filter
 */
interface FilterInterface extends ObjectHandlable
{
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
     * Add a filter based on priority
     *
     * @param FilterInterface 	$filter A Filter
     * @param integer	        $priority The command priority, usually between 1 (high priority) and 5 (lowest),
     *                                    default is 3. If no priority is set, the filter priority will be used
     *                                    instead.
     *
     * @return FilterInterface
     */
    public function addFilter(FilterInterface $filter, $priority = null);

    /**
     * Get a list of error that occurred during sanitize or validate
     *
     * @return array
     */
    public function getErrors();
}