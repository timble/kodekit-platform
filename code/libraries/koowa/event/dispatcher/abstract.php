<?php
/**
 * @version     $Id$
 * @package     Koowa_Event
 * @subpackage 	Dispatcher
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Abstract Event Dispatcher Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Event
 * @subpackage 	Dispatcher
 */
abstract class KEventDispatcherAbstract extends KObject implements KEventDispatcherInterface
{
    /**
     * List of event listeners
     *
     * An associative array of event listeners queues where keys are holding the event name and the value
     * is an KObjectQueue object.
     *
     * @var array
     */
    protected $_listeners;

    /**
     * List of event subscribers
     *
     * Associative array of subscribers, where key holds the subscriber handle and the value the subscriber
     * object.
     *
     * @var array
     */
    protected $_subscribers;

    /**
     * The event object
     *
     * @var KEvent
     */
    protected $_event = null;

    /**
     * Constructor.
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_subscribers = array();
        $this->_listeners   = array();
    }

    /**
     * Dispatches an event by dispatching arguments to all listeners that handle the event.
     *
     * @param   string  The event name
     * @param   object|array   An array, a KConfig or a KEvent object
     * @return  KEvent
     */
    public function dispatchEvent($name, $event = array())
    {
        $result = array();

        //Make sure we have an event object
        if (!$event instanceof KEvent) {
            $event = new KEvent($event);
        }

        $event->setName($name)
              ->setDispatcher($this);

        //Notify the listeners
        $listeners = $this->getListeners($name);

        foreach ($listeners as $listener)
        {
            call_user_func($listener, $event);

            if (!$event->canPropagate()) {
                break;
            }
        }

        return $event;
    }

    /**
     * Add an event listener
     *
     * @param  string    $name       The event name
     * @param  callable  $listener   The listener
     * @param  integer   $priority   The event priority, usually between 1 (high priority) and 5 (lowest),
     *                               default is 3. If no priority is set, the command priority will be used
     *                               instead.
     * @return KEventDispatcherAbstract
     */
    public function addEventListener($name, $listener, $priority = KEvent::PRIORITY_NORMAL)
    {
        if (!is_callable($listener))
        {
            throw new \InvalidArgumentException(
                'The listener must be a callable, "'.gettype($listener).'" given.'
            );
        }

        $this->_listeners[$name][$priority][] = $listener;

        ksort($this->_listeners[$name]);
        return $this;
    }

    /**
     * Remove an event listener
     *
     * @param   string    $name      The event name
     * @param   callable  $listener  The listener
     * @return  KEventDispatcherAbstract
     */
    public function removeEventListener($name, $listener)
    {
        if (!is_callable($listener))
        {
            throw new \InvalidArgumentException(
                'The listener must be a callable, "'.gettype($listener).'" given.'
            );
        }

        if (isset($this->listeners[$name]))
        {
            foreach ($this->_listeners[$name] as $priority => $listeners)
            {
                if (false !== ($key = array_search($listener, $listeners))) {
                    unset($this->_listeners[$name][$priority][$key]);
                }
            }
        }

        return $this;
    }

    /**
     * Get a list of listeners for a specific event
     *
     * @param   string          The event name
     * @return  KObjectQueue    An object queue containing the listeners
     */
    public function getListeners($name)
    {
        $result = array();
        if (isset($this->_listeners[$name]))
        {
            foreach($this->_listeners[$name] as $priority => $listeners) {
                $result = array_merge($result, $listeners);
            }
        }

        return $result;
    }

    /**
     * Check if we are listening to a specific event
     *
     * @param   string  The event name
     * @return  boolean    TRUE if we are listening for a specific event, otherwise FALSE.
     */
    public function hasListeners($name)
    {
        $result = false;
        if (isset($this->_listeners[$name])) {
            $result = (boolean)count($this->_listeners[$name]);
        }

        return $result;
    }

    /**
     * Add an event subscriber
     *
     * @param  object    The event subscriber to add
     * @return  KEventDispatcherAbstract
     */
    public function addEventSubscriber(KEventSubscriberInterface $subscriber, $priority = null)
    {
        $handle = $subscriber->getHandle();

        if (!isset($this->_subscribers[$handle]))
        {
            $subscriptions = $subscriber->getSubscriptions();
            $priority = is_int($priority) ? $priority : $subscriber->getPriority();

            foreach ($subscriptions as $name => $listener) {
                $this->addEventListener($name, $listener, $priority);
            }

            $this->_subscribers[$handle] = $subscriber;
        }

        return $this;
    }

    /**
     * Remove an event subscriber
     *
     * @param  object    The event subscriber to remove
     * @return  KEventDispatcherAbstract
     */
    public function removeEventSubscriber(KEventSubscriberInterface $subscriber)
    {
        $handle = $subscriber->getHandle();

        if (isset($this->_subscribers[$handle]))
        {
            $subscriptions = $subscriber->getSubscriptions();

            foreach ($subscriptions as $name => $listener) {
                $this->removeEventListener($name, $listener);
            }

            unset($this->_subscribers[$handle]);
        }

        return $this;
    }

    /**
     * Gets the event subscribers
     *
     * @return array    An associative array of event subscribers, keys are the subscriber handles
     */
    public function getSubscribers()
    {
        return $this->_subscribers;
    }

    /**
     * Check if the handler is connected to a dispatcher
     *
     * @param  object  The event dispatcher
     * @return boolean TRUE if the handler is already connected to the dispatcher. FALSE otherwise.
     */
    public function isSubscribed(KEventSubscriberInterface $subscriber)
    {
        $handle = $subscriber->getHandle();
        return isset($this->_subscribers[$handle]);
    }

    /**
     * Set the priority of an event
     *
     * @param  string   $name      The event name
     * @param  object   $listener  The listener
     * @param  integer  $priority  The event priority
     * @return  KEventDispatcherAbstract
     */
    public function setEventPriority($name, $listener, $priority)
    {
        if (!is_callable($listener))
        {
            throw new \InvalidArgumentException(
                'The listener must be a callable, "'.gettype($listener).'" given.'
            );
        }

        if (isset($this->listeners[$name]))
        {
            foreach ($this->_listeners[$name] as $priority => $listeners)
            {
                if (false !== ($key = array_search($listener, $listeners)))
                {
                    unset($this->_listeners[$name][$priority][$key]);
                    $this->_listeners[$name][$priority][] = $listener;
                }
            }
        }

        return $this;
    }

    /**
     * Get the priority of an event
     *
     * @param   string   $name      The event name
     * @param   object   $listener  The listener
     * @return  integer|false The event priority or FALSE if the event isn't listened for.
     */
    public function getEventPriority($name, $listener)
    {
        $result = false;

        if (!is_callable($listener))
        {
            throw new \InvalidArgumentException(
                'The listener must be a callable, "'.gettype($listener).'" given.'
            );
        }

        if (isset($this->listeners[$name]))
        {
            foreach ($this->_listeners[$name] as $priority => $listeners)
            {
                if (false !== ($key = array_search($listener, $listeners)))
                {
                    $result = $priority;
                    break;
                }
            }
        }

        return $result;
    }
}