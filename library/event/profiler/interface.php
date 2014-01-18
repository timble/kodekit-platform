<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Event Profiler Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Event
 */
interface EventProfilerInterface
{
    /**
     * Disables the profiler.
     */
    public function disable();

    /**
     * Enables the profiler.
     */
    public function enable();

    /**
     * Get the list of event profiles
     *
     * @return array Array of event profiles
     */
    public function getProfiles();
    
	/**
     * Get information about current memory usage.
     *
     * @return int The memory usage
     * @link PHP_MANUAL#memory_get_usage
     */
    public function getMemoryUsage();

    /**
     * Returns information about a listener
     *
     * @param callable $listener  The listener
     * @return array Information about the listener
     */
    public function getListenerInfo($listener);

    /**
     * Check of the profiler is enabled
     *
     * @return bool
     */
    public function isEnabled();
}