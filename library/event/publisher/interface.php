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
 * Event Publisher Interface
 *
 * Interface provides a topic based event publishing mechanism. Higher priority event listeners are called first.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Event
 */
interface EventPublisherInterface
{
    /**
     * Publish an event by calling all listeners that have registered to receive it.
     *
     * @param  string|EventInterface              $event      The event name or a KEventInterface object
     * @param  array|\Traversable|EventInterface  $attributes An associative array, an object implementing the
     *                                                        EventInterface or a Traversable object
     * @param  mixed                              $target     The event target
     * @throws \InvalidArgumentException  If the event is not a string or does not implement the KEventInterface
     * @return null|EventInterface Returns the event object. If the chain is not enabled will return NULL.
     */
    public function publishEvent($event, $attributes = array(), $target = null);

    /**
     * Add an event listener
     *
     * @param string|EventInterface  $event     The event name or a EventInterface object
     * @param callable               $listener  The listener
     * @param integer                $priority  The event priority, usually between 1 (high priority) and 5 (lowest),
     *                                            default is 3 (normal)
     * @throws \InvalidArgumentException If the listener is not a callable
     * @throws \InvalidArgumentException If the event is not a string or does not implement the EventInterface
     * @return EventPublisherAbstract
     */
    public function addListener($event, $listener, $priority = EventInterface::PRIORITY_NORMAL);

    /**
     * Remove an event listener
     *
     * @param string|EventInterface  $event     The event name or a EventInterface object
     * @param callable                $listener  The listener
     * @throws \InvalidArgumentException If the listener is not a callable
     * @throws \InvalidArgumentException  If the event is not a string or does not implement the KEventInterface
     * @return EventPublisherAbstract
     */
    public function removeListener($event, $listener);

    /**
     * Get a list of listeners for a specific event
     *
     * @param string|EventInterface  $event     The event name or a EventInterface object
     * @throws \InvalidArgumentException  If the event is not a string or does not implement the KEventInterface
     * @return array An array containing the listeners ordered by priority
     */
    public function getListeners($event);

    /**
     * Set the priority of a listener
     *
     * @param  string|EventInterface  $event     The event name or a EventInterface object
     * @param  callable               $listener  The listener
     * @param  integer                $priority  The event priority
     * @throws \InvalidArgumentException If the listener is not a callable
     * @throws \InvalidArgumentException If the event is not a string or does not implement the EventInterface
     * @return EventPublisherAbstract
     */
    public function setListenerPriority($event, $listener, $priority);

    /**
     * Get the priority of an event
     *
     * @param string|EventInterface  $event     The event name or a EventInterface object
     * @param callable               $listener  The listener
     * @throws \InvalidArgumentException If the listener is not a callable
     * @throws \InvalidArgumentException  If the event is not a string or does not implement the EventInterface
     * @return integer|false The event priority or FALSE if the event isn't listened for.
     */
    public function getListenerPriority($event, $listener);

    /**
     * Enable the profiler
     *
     * @return  EventPublisherInterface
     */
    public function setEnabled($enabled);

    /**
     * Check of the publisher is enabled
     *
     * @return bool
     */
    public function isEnabled();
}