<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Event Mixin
 *
 * Class can be used as a mixin in classes that want to implement a an event publisher and allow adding and removing
 * event listeners and subscribers.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Event
 */
class EventMixin extends ObjectMixinAbstract implements EventMixinInterface
{
    /**
     * Event publisher object
     *
     * @var EventPublisherInterface
     */
    private $__event_publisher;

    /**
     * List of event subscribers
     *
     * Associative array of event subscribers, where key holds the subscriber identifier string
     * and the value is an identifier object.
     *
     * @var  array
     */
    private $__event_subscribers = array();

    /**
     * Object constructor
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options
     * @throws \InvalidArgumentException
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        if (is_null($config->event_publisher)) {
            throw new \InvalidArgumentException('event_publisher [EventPublisherInterface] config option is required');
        }

        //Set the event dispatcher
        $this->__event_publisher = $config->event_publisher;

        //Add the event listeners
        foreach ($config->event_listeners as $event => $listener) {
            $this->addEventListener($event, $listener);
        }

        //Add the event subscribers
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
            'event_publisher'   => 'event.publisher',
            'event_subscribers' => array(),
            'event_listeners'   => array(),
        ));

        parent::_initialize($config);
    }

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
    public function publishEvent($event, $attributes = array(), $target = null)
    {
        return $this->getEventPublisher()->publishEvent($event, $attributes, $target);
    }

    /**
     * Get the event publisher
     *
     * @throws \UnexpectedValueException
     * @return EventPublisherInterface
     */
    public function getEventPublisher()
    {
        if(!$this->__event_publisher instanceof EventPublisherInterface)
        {
            $this->__event_publisher = $this->getObject($this->__event_publisher);

            if(!$this->__event_publisher instanceof EventPublisherInterface)
            {
                throw new \UnexpectedValueException(
                    'EventPublisher: '.get_class($this->__event_publisher).' does not implement KEventPublisherInterface'
                );
            }
        }

        return $this->__event_publisher;
    }

    /**
     * Set the event publisher
     *
     * @param   EventPublisherInterface  $publisher An event publisher object
     * @return  ObjectInterface  The mixer
     */
    public function setEventPublisher(EventPublisherInterface $publisher)
    {
        $this->__event_publisher = $publisher;
        return $this->getMixer();
    }

    /**
     * Add an event listener
     *
     * @param string|EventInterface  $event     The event name or a KEventInterface object
     * @param callable               $listener  The listener
     * @param integer                $priority  The event priority, usually between 1 (high priority) and 5 (lowest),
     *                                           default is 3 (normal)
     * @throws \InvalidArgumentException If the listener is not a callable
     * @throws \InvalidArgumentException  If the event is not a string or does not implement the KEventInterface
     * @return ObjectInterface The mixer
     */
    public function addEventListener($event, $listener, $priority = EventInterface::PRIORITY_NORMAL)
    {
        $this->getEventPublisher()->addListener($event, $listener, $priority);
        return $this->getMixer();
    }

    /**
     * Remove an event listener
     *
     * @param string|EventInterface  $event      The event name or a KEventInterface object
     * @param callable                $listener  The listener
     * @throws \InvalidArgumentException If the listener is not a callable
     * @throws \InvalidArgumentException  If the event is not a string or does not implement the KEventInterface
     * @return ObjectInterface The mixer
     */
    public function removeEventListener($event, $listener)
    {
        $this->getEventPublisher()->removeListener($event, $listener);
        return $this->getMixer();
    }

    /**
     * Add an event subscriber
     *
     * @param mixed  $subscriber An object that implements ObjectInterface, ObjectIdentifier object
     *                           or valid identifier string
     * @param array  $config     An optional associative array of configuration options
     * @return ObjectInterface The mixer
     */
    public function addEventSubscriber($subscriber, $config = array())
    {
        if (!($subscriber instanceof EventSubscriberInterface)) {
            $subscriber = $this->getEventSubscriber($subscriber, $config);
        }

        $subscriber->subscribe($this->getEventPublisher());
        return $this;
    }

    /**
     * Remove an event subscriber
     *
     * @param  EventSubscriberInterface  $subscriber An event subscriber
     * @return ObjectInterface The mixer
     */
    public function removeEventSubscriber(EventSubscriberInterface $subscriber)
    {
        $subscriber->unsubscribe($this->getEventPublisher());
        return $this->getMixer();
    }

    /**
     * Get a event subscriber by identifier
     *
     * The subscriber will be created if does not exist yet, otherwise the existing subscriber will be returned.
     *
     * @param  mixed $subscriber An object that implements ObjectInterface, ObjectIdentifier object
     *                          or valid identifier string
     * @param  array  $config   An optional associative array of configuration settings
     * @throws \UnexpectedValueException    If the subscriber is not implementing the EventSubscriberInterface
     * @return EventSubscriberInterface
     */
    public function getEventSubscriber($subscriber, $config = array())
    {
        if (!($subscriber instanceof ObjectIdentifier))
        {
            //Create the complete identifier if a partial identifier was passed
            if (is_string($subscriber) && strpos($subscriber, '.') === false)
            {
                $identifier = $this->getIdentifier()->toArray();
                $identifier['path'] = array('event', 'subscriber');
                $identifier['name'] = $subscriber;

                $identifier = $this->getIdentifier($identifier);
            }
            else $identifier = $this->getIdentifier($subscriber);
        }
        else $identifier = $subscriber;

        if (!isset($this->__event_subscribers[(string)$identifier]))
        {
            $subscriber = $this->getObject($identifier, $config);

            //Check the event subscriber interface
            if (!($subscriber instanceof EventSubscriberInterface))
            {
                throw new \UnexpectedValueException(
                    "Event Subscriber $identifier does not implement KEventSubscriberInterface"
                );
            }
        }
        else $subscriber = $this->__event_subscribers[(string)$identifier];

        return $subscriber;
    }

    /**
     * Gets the event subscribers
     *
     * @return array An array of event subscribers
     */
    public function getEventSubscribers()
    {
        return array_values($this->__event_subscribers);
    }
}