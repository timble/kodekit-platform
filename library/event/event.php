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
 * Event
 *
 * You can call the method stopPropagation() to abort the execution of further listeners in your event listener.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Event
 */
class Event extends ObjectConfig implements EventInterface
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
     * The propagation state of the event
     * 
     * @var boolean 
     */
    protected $_propagate = true;
 	
 	/**
     * The event name
     *
     * @var array
     */
    protected $_name;
    
    /**
     * Target of the event
     *
     * @var ObjectInterface
     */
    protected $_target;
    
    /**
     * Dispatcher of the event
     * 
     * @var EventDispatcherInterface
     */
    protected $_dispatcher;

    /**
     * Set an event property
     *
     * @param  string $name
     * @param  mixed  $value
     * @return void
     */
    public function set($name, $value)
    {
        if (is_array($value)) {
            $this->_data[$name] = new ObjectConfig($value);
        } else {
            $this->_data[$name] = $value;
        }
    }
         
    /**
     * Get the event name
     * 
     * @return string	The event name
     */
    public function getName()
    {
        return $this->_name;
    }
    
    /**
     * Set the event name
     *
     * @param string $name  The event name
     * @return Event
     */
    public function setName($name)
    {
        $this->_name = $name;
        return $this;
    }
    
    /**
     * Get the event target
     *
     * @return object	The event target
     */
    public function getTarget()
    {
        return $this->_target;
    }
    
    /**
     * Set the event target
     *
     * @param object $target The event target
     * @return Event
     */
    public function setTarget(ObjectInterface $target)
    {
        $this->_target = $target;
        return $this;
    }
    
    /**
     * Stores the EventDispatcher that dispatches this Event
     *
     * @param EventDispatcherInterface $dispatcher
     * @return Event
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->_dispatcher = $dispatcher;
        return $this;
    }
    
    /**
     * Returns the EventDispatcher that dispatches this Event
     *
     * @return EventDispatcherInterface
     */
    public function getDispatcher()
    {
        return $this->_dispatcher;
    }
    
    /**
     * Returns whether further event listeners should be triggered.
     *
     * @return boolean 	TRUE if the event can propagate. Otherwise FALSE
     */
    public function canPropagate()
    {
        return $this->_propagate;
    }

    /**
     * Stops the propagation of the event to further event listeners.
     *
     * If multiple event listeners are connected to the same event, no further event listener will be triggered once
     * any trigger calls stopPropagation().
     *
     * @return Event
     */
    public function stopPropagation()
    {
        $this->_propagate = false;
        return $this;
    }
}