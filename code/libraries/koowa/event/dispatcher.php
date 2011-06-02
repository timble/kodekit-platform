<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Event
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Class to handle dispatching of events.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Event
 */
class KEventDispatcher extends KObject
{
    /**
	 * An associative array of event listeners queues 
	 * 
	 * The keys are holding the event namse and the value is 
	 * an KObjectQueue object.
	 *
	 * @var array
	 */
	protected $_listeners;
	
	/**
     * Constructor.
     *
     * @param   object  An optional KConfig object with configuration options
     */
	public function __construct(KConfig $config = null) 
	{
		$this->_listeners = array();
	}
 	
 	/**
     * Dispatches an event by dispatching arguments to all listeners that handle
     * the event and returning their return values.
     *
     * @param   object   The KEvent being dispatched 
     * @return  KEventDispatcher
     */
    public function dispatchEvent(KEvent $event)
    {
        $result = array();
        
        $name = $event->getName();
        
        if(isset($this->_listeners[$name])) 
        {
            foreach($this->_listeners[$name] as $listener) 
            {
                $listener->$event($event);
                
                if (!$event->canPropagate()) {
                    break;
                }
            }
        }
        
        return $this;
    }
         
    /**
     * Add an event listener
     *
     * @param  string  The event name
     * @param  object  An object implementing the KObjectHandlable interface
     * @param  integer The event priority, usually between 1 (high priority) and 5 (lowest), 
     *                 default is 3. If no priority is set, the command priority will be used 
     *                 instead.
     * @return KEventDispatcher
     */
    public function addEventListener($event, KObjectHandlable $listener, $priority = KEvent::PRIORITY_NORMAL)
    {
        if(is_object($listener))
        {
            if(!isset($this->_listeners[$event])) {
                $this->_listeners[$event] = new KObjectQueue();
            }
            
            $this->_listeners[$event]->enqueue($listener, $priority);
        }
            
        return $this;
    }

    /**
     * Remove an event listener
     *
     * @param   string  The event name
     * @param   object  An object implementing the KObjectHandlable interface
     * @return  KEventDispatcher
     */
    public function removeEventListener($event, KObjectHandable $listener)
    {
        if(is_object($listener))
        {
            if(isset($this->_listeners[$event])) {
                $this->_listeners[$event]->dequeue($listener);
            }
        }
        
        return $this;
    }
    
    /**
     * Get a list of listeners for a specific event
     *
     * @param   string  		The event name
     * @return  KObjectQueue	An object queue containing the listeners
     */
    public function getListeners($event)
    {
        $result = array();
        if(isset($this->_listeners[$event])) {
            $result = $this->_listeners[$event];
        }
        
        return $result;
    }
    
    /**
     * Check if we are listening to a specific event
     *
     * @param   string  The event name
     * @return  boolean	TRUE if we are listening for a specific event, otherwise FALSE.
     */
    public function hasListeners($event)
    {
        $result = false;
        if(isset($this->_listeners[$event])) {
             $result = (boolean) count($this->_listereners[$event]);
        }
        
        return $result;
    }
    
	/**
     * Set the priority of an event
     * 
     * @param  string    The event name
     * @param  object    An object implementing the KObjectHandlable interface
     * @param  integer   The event priority
     * @return KCommandChain
     */
    public function setEventPriority($event, KObjectHandable $listener, $priority)
    {
        if(isset($this->_listeners[$event])) {
            $this->_listeners[$event]->setPriority($listener, $priority);
        }
        
        return $this;
    }
    
    /**
     * Get the priority of an event
     * 
     * @param   string  The event name
     * @param   object  An object implementing the KObjectHandlable interface
     * @return  integer|false The event priority or FALSE if the event isn't listened for.
     */
    public function getEventPriority($event, KObjectHandable $listener)
    {
        $result = false;
        
        if(isset($this->_listeners[$event])) {
            $result = $this->_listeners[$event]->getPriority($listener);
        }
        
        return $result;
    }
}