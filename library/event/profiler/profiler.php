<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Event Profiler
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Event
 */
class EventProfiler extends ObjectDecorator implements EventProfilerInterface, EventDispatcherInterface
{
   /**
    * The start time
    * 
    * @var int
    */
    protected $_start = 0;

    /**
     * Enabled status of the profiler
     *
     * @var boolean
     */
    protected $_enabled;
    
    /**
     * Array of event profiles
     *
     * @var array
     */
    protected $_profiles;
 	
 	/**
     * Constructor.
     *
     * @param ObjectConfig $config An optional Library\ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {          
        parent::__construct($config);
        
        $this->_start = $config->start;
    }
    
	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config  An optional Library\ObjectConfig object with configuration options
     * @return void
	 */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
        	'start'   => microtime(true),
        ));

       parent::_initialize($config);
    }

    /**
     * Enable the profiler
     *
     * @return  EventProfiler
     */
    public function enable()
    {
        $this->_enabled = true;
        return $this;
    }

    /**
     * Disable the profiler
     *
     * @return  EventProfiler
     */
    public function disable()
    {
        $this->_enabled = false;
        return $this;
    }

    /**
     * Dispatches an event by dispatching arguments to all listeners that handle the event and returning their return
     * values.
     *
     * This function will add a mark to the profiler for each event dispatched
     *
     * @param   string        $name  The event name
     * @param   object|array  $event An array, a Library\ObjectConfig or a Library\Event object
     * @return  EventDispatcher
     */
    public function dispatch($name, $event = array())
    {
        if($this->isEnabled())
        {
            $this->_profiles[] = array(
                'message' => $name,
                'time'    => $this->getElapsedTime(),
                'memory'  => $this->getMemoryUsage(),
                'target'  => $event->getTarget()->getIdentifier()
            );
        }

        return $this->getDelegate()->dispatch($name, $event);
    }

    /**
     * Add an event listener
     *
     * @param  string    $name       The event name
     * @param  callable  $listener   The listener
     * @param  integer   $priority   The event priority, usually between 1 (high priority) and 5 (lowest),
     *                               default is 3. If no priority is set, the command priority will be used
     *                               instead.
     * @throws \InvalidArgumentException If the listener is not a callable
     * @return EventDispatcherAbstract
     */
    public function addListener($name, $listener, $priority = Event::PRIORITY_NORMAL)
    {
        $this->getDelegate()->addListener($name, $listener, $priority);
        return $this;
    }

    /**
     * Remove an event listener
     *
     * @param   string    $name      The event name
     * @param   callable  $listener  The listener
     * @throws \InvalidArgumentException If the listener is not a callable
     * @return  EventDispatcherAbstract
     */
    public function removeListener($name, $listener)
    {
        $this->getDelegate()->removeListener($name, $listener);
        return $this;
    }

    /**
     * Get a list of listeners for a specific event
     *
     * @param   string  $name  The event name
     * @return  ObjectQueue    An object queue containing the listeners
     */
    public function getListeners($name)
    {
        return $this->getDelegate()->getListeners($name);
    }

    /**
     * Check if we are listening to a specific event
     *
     * @param   string  $name The event name
     * @return  boolean  TRUE if we are listening for a specific event, otherwise FALSE.
     */
    public function hasListeners($name)
    {
        return $this->getDelegate()->hasListeners($name);
    }

    /**
     * Add an event subscriber
     *
     * @param  EventSubscriberInterface $subscriber The event subscriber to add
     * @return  EventDispatcherAbstract
     */
    public function addSubscriber(EventSubscriberInterface $subscriber, $priority = null)
    {
        $this->getDelegate()->addSubscriber($subscriber);
        return $this;
    }

    /**
     * Remove an event subscriber
     *
     * @param  EventSubscriberInterface $subscriber The event subscriber to remove
     * @return  EventDispatcherAbstract
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->getDelegate()->removeSubscriber($subscriber);
        return $this;
    }

    /**
     * Gets the event subscribers
     *
     * @return array    An associative array of event subscribers, keys are the subscriber handles
     */
    public function getSubscribers()
    {
        return $this->getDelegate()->getSubscribers();
    }

    /**
     * Check if the handler is connected to a dispatcher
     *
     * @param  EventSubscriberInterface $subscriber  The event subscriber
     * @return boolean TRUE if the handler is already connected to the dispatcher. FALSE otherwise.
     */
    public function isSubscribed(EventSubscriberInterface $subscriber)
    {
        return $this->getDelegate()->isSubscribed($subscriber);
    }

    /**
     * Set the priority of an event
     *
     * @param  string   $name      The event name
     * @param  object   $listener  The listener
     * @param  integer  $priority  The event priority
     * @return  EventDispatcherAbstract
     */
    public function setPriority($name, $listener, $priority)
    {
        return $this->getDelegate()->setPriority($name, $listener, $priority);
    }

    /**
     * Get the priority of an event
     *
     * @param   string   $name      The event name
     * @param   object   $listener  The listener
     * @return  integer|false The event priority or FALSE if the event isn't listened for.
     */
    public function getPriority($name, $listener)
    {
        return $this->getDelegate()->getPriority($name, $listener);
    }

    /**
     * Get the list of event profiles
     *
     * @return array Array of event profiles
     */
    public function getProfiles()
    {
        return $this->_profiles;
    }
    
	/**
     * Get information about current memory usage.
     *
     * @return int The memory usage
     * @link PHP_MANUAL#memory_get_usage
     */
    public function getMemoryUsage()
    {
        $size = memory_get_usage(true);
        $unit = array('b','kb','mb','gb','tb','pb');
                
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }
    
	/**
	 * Gets the total time elapsed for all calls of this timer.
	 *
	 * @return float Time in seconds
	 */
    public function getElapsedTime()
    {
        return microtime(true) - $this->_start;
    }

    /**
     * Check of the command chain is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->_enabled;
    }

    /**
     * Set the decorated event dispatcher
     *
     * @param   EventDispatcherInterface $delegate The decorated event dispatcher
     * @return  ObjectDecoratorAbstract
     * @throws  \InvalidArgumentException If the delegate is not an event dispatcher
     */
    public function setDelegate($delegate)
    {
        if (!$delegate instanceof EventDispatcherInterface) {
            throw new \InvalidArgumentException('EventDispatcher: '.get_class($delegate).' does not implement EventDispatcherInterface');
        }

        return parent::setDelegate($delegate);
    }

    /**
     * Set the decorated object
     *
     * @return EventDispatcherInterface
     */
    public function getDelegate()
    {
        return parent::getDelegate();
    }
}