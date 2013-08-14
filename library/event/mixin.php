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
 * Event Mixin
 *
 * Class can be used as a mixin in classes that want to implement a an event dispatcher and allow adding and removing
 * listeners.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Event
 */
class EventMixin extends ObjectMixinAbstract
{
    /**
     * Event dispatcher object
     *
     * @var EventDispatcherInterface
     */
    protected $_event_dispatcher;

    /**
     * List of event subscribers
     *
     * Associative array of event subscribers, where key holds the subscriber identifier string
     * and the value is an identifier object.
     *
     * @var    array
     */
    protected $_event_subscribers = array();

    /**
     * Object constructor
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        if (is_null($config->event_dispatcher)) {
            throw new \InvalidArgumentException('event_dispatcher [EventDispatcherInterface] config option is required');
        }

        //Set the event dispatcher
        $this->_event_dispatcher = $config->event_dispatcher;

        //Add the event listeners
        foreach ($config->event_listeners as $event => $listener) {
            $this->addEventListener($event, $listener);
        }

        //Add the event handlers
        $subscribers = (array) ObjectConfig::unbox($config->event_subscribers);

        foreach ($subscribers as $key => $value)
        {
            if (is_numeric($key)) {
                $this->addEventSubscriber($value);
            } else {
                $this->addEventSubscriber($key, $value);
            }
        }
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'event_dispatcher'  => null,
            'event_subscribers' => array(),
            'event_listeners'   => array(),
        ));

        parent::_initialize($config);
    }

    /**
     * Get the event dispatcher
     *
     * @return  EventDispatcherInterface
     */
    public function getEventDispatcher()
    {
        if(!$this->_event_dispatcher instanceof EventDispatcherInterface)
        {
            $this->_event_dispatcher = $this->getObject($this->_event_dispatcher);

            //Make sure the request implements ControllerRequestInterface
            if(!$this->_event_dispatcher instanceof EventDispatcherInterface)
            {
                throw new \UnexpectedValueException(
                    'EventDispatcher: '.get_class($this->_event_dispatcher).' does not implement EventDispatcherInterface'
                );
            }
        }

        return $this->_event_dispatcher;
    }

    /**
     * Set the chain of command object
     *
     * @param   object  $dispatcher An event dispatcher object
     * @return  Object  The mixer object
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->_event_dispatcher = $dispatcher;
        return $this->getMixer();
    }

    /**
     * Add an event listener
     *
     * @param  string   $event     The event name
     * @param  callable $listener  The listener
     * @param  integer $priority   The event priority, usually between 1 (high priority) and 5 (lowest),
     *                             default is 3. If no priority is set, the command priority will be used
     *                             instead.
     * @return  Object The mixer objects
     */
    public function addEventListener($event, $listener, $priority = Event::PRIORITY_NORMAL)
    {
        $this->getEventDispatcher()->addEventListener($event, $listener, $priority);
        return $this->getMixer();
    }

    /**
     * Remove an event listener
     *
     * @param   string   $event     The event name
     * @param   callable $listener  The listener
     * @return  Object  The mixer object
     */
    public function removeEventListener($event, $listener)
    {
        $this->getEventDispatcher()->removeEventListener($event, $listener);
        return $this->getMixer();
    }

    /**
     * Add an event subscriber
     *
     * @param   mixed  $subscriber An object that implements ObjectInterface, ObjectIdentifier object
     *                            or valid identifier string
     * @param  array  $config   An optional associative array of configuration settings
     * @param  integer $priority The event priority, usually between 1 (high priority) and 5 (lowest),
     *                 default is 3. If no priority is set, the command priority will be used
     *                 instead.
     * @return  Object    The mixer object
     */
    public function addEventSubscriber($subscriber, $config = array(), $priority = null)
    {
        if (!($subscriber instanceof EventSubscriberInterface)) {
            $subscriber = $this->getEventSubscriber($subscriber, $config);
        }

        $priority = is_int($priority) ? $priority : $subscriber->getPriority();
        $this->getEventDispatcher()->addEventSubscriber($subscriber, $priority);

        return $this;
    }

    /**
     * Remove an event subscriber
     *
     * @param   mixed  $subscriber An object that implements ObjectInterface, ObjectIdentifier object
     *                             or valid identifier string
     * @return  Object  The mixer object
     */
    public function removeEventSubscriber($subscriber)
    {
        if (!($subscriber instanceof EventSubscriberInterface)) {
            $subscriber = $this->getEventSubscriber($subscriber);
        }

        $this->getEventDispatcher()->removeEventSubscriber($subscriber);
        return $this->getMixer();
    }

    /**
     * Get a event subscriber by identifier
     *
     * @param  mixed $subscriber An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @param  array  $config   An optional associative array of configuration settings
     * @throws \DomainException    If the subscriber is not implementing the EventSubscriberInterface
     * @return EventSubscriberInterface
     */
    public function getEventSubscriber($subscriber, $config = array())
    {
        if (!($subscriber instanceof ObjectIdentifier))
        {
            //Create the complete identifier if a partial identifier was passed
            if (is_string($subscriber) && strpos($subscriber, '.') === false)
            {
                $identifier = clone $this->getIdentifier();
                $identifier->path = array('event', 'subscriber');
                $identifier->name = $subscriber;
            }
            else $identifier = $this->getIdentifier($subscriber);
        }
        else $identifier = $subscriber;

        if (!isset($this->_event_subscribers[(string)$identifier]))
        {
            $config['event_dispatcher'] = $this->getEventDispatcher();
            $subscriber = $this->getObject($identifier, $config);

            //Check the event subscriber interface
            if (!($subscriber instanceof EventSubscriberInterface))
            {
                throw new \UnexpectedValueException(
                    "Event Subscriber $identifier does not implement EventSubscriberInterface"
                );
            }
        }
        else $subscriber = $this->_event_subscribers[(string)$identifier];

        return $subscriber;
    }
}