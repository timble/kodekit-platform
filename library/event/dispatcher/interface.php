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
 * Event Dispatcher Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Event
 */
interface EventDispatcherInterface
{
    /**
     * Dispatches an event by dispatching arguments to all listeners that handle the event.
     *
     * @param   string         $name  The event name
     * @param   object|array   $event An array, a ObjectConfig or a Event object
     * @return  Event
     */
    public function dispatchEvent($name, $event = array());

    /**
     * Add an event listener
     *
     * @param  string    $name       The event name
     * @param  callable  $listener   The listener
     * @param  integer   $priority   The event priority, usually between 1 (high priority) and 5 (lowest),
     *                               default is 3. If no priority is set, the command priority will be used
     *                               instead.
     * @return EventDispatcherAbstract
     */
    public function addEventListener($name, $listener, $priority = Event::PRIORITY_NORMAL);

    /**
     * Remove an event listener
     *
     * @param   string    $name      The event name
     * @param   callable  $listener  The listener
     * @return  EventDispatcherAbstract
     */
    public function removeEventListener($name, $listener);

    /**
     * Get a list of listeners for a specific event
     *
     * @param   string  $name  The event name
     * @return  ObjectQueue    An object queue containing the listeners
     */
    public function getListeners($name);

    /**
     * Check if we are listening to a specific event
     *
     * @param   string  $name The event name
     * @return  boolean  TRUE if we are listening for a specific event, otherwise FALSE.
     */
    public function hasListeners($name);

    /**
     * Add an event subscriber
     *
     * @param  EventSubscriberInterface $subscriber The event subscriber to add
     * @return  EventDispatcherAbstract
     */
    public function addEventSubscriber(EventSubscriberInterface $subscriber, $priority = null);

    /**
     * Remove an event subscriber
     *
     * @param  EventSubscriberInterface $subscriber The event subscriber to remove
     * @return  EventDispatcherAbstract
     */
    public function removeEventSubscriber(EventSubscriberInterface $subscriber);

    /**
     * Gets the event subscribers
     *
     * @return array    An associative array of event subscribers, keys are the subscriber handles
     */
    public function getSubscribers();

    /**
     * Check if the handler is connected to a dispatcher
     *
     * @param  object  The event dispatcher
     * @return boolean TRUE if the handler is already connected to the dispatcher. FALSE otherwise.
     */
    public function isSubscribed(EventSubscriberInterface $subscriber);

    /**
     * Set the priority of an event
     *
     * @param  string   $name      The event name
     * @param  callable $listener  The listener
     * @param  integer  $priority  The event priority
     * @return  EventDispatcherInterface
     */
    public function setEventPriority($name, $listener, $priority);

    /**
     * Get the priority of an event
     *
     * @param   string    $name      The event name
     * @param   callable  $listener  The listener
     * @return  integer|false The event priority or FALSE if the event isn't listened for.
     */
    public function getEventPriority($name, $listener);


}