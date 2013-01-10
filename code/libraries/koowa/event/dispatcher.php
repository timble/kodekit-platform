<?php
/**
 * @version     $Id$
 * @package     Koowa_Event
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Class to handle dispatching of events.
 *
 * @author      Johan Janssens <johan@nooku.org>
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
	public function __construct(KConfig $config = null)
	{
		parent::__construct($config);

	    $this->_listeners = array();
	}

 	/**
     * Dispatches an event by dispatching arguments to all listeners that handle
     * the event and returning their return values.
     *
     * @param   string  The event name
     * @param   object|array   An array, a KConfig or a KEvent object
     * @return  KEventDispatcher
     */
    public function dispatchEvent($name, $event = array())
    {
        $result = array();

        //Make sure we have an event object
        if(!$event instanceof KEvent) {
            $event = new KEvent($name, $event);
        }

        //Nofity the listeners
        if(isset($this->_listeners[$name]))
        {
            foreach($this->_listeners[$name] as $listener)
            {
                $listener->$name($event);

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
    public function addEventListener($name, KObjectHandlable $listener, $priority = KEvent::PRIORITY_NORMAL)
    {
        if(is_object($listener))
        {
            if(!isset($this->_listeners[$name])) {
                $this->_listeners[$name] = new KObjectQueue();
            }

            $this->_listeners[$name]->enqueue($listener, $priority);
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
    public function removeEventListener($name, KObjectHandable $listener)
    {
        if(is_object($listener))
        {
            if(isset($this->_listeners[$name])) {
                $this->_listeners[$name]->dequeue($listener);
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
    public function getListeners($name)
    {
        $result = array();
        if(isset($this->_listeners[$name])) {
            $result = $this->_listeners[$name];
        }

        return $result;
    }

    /**
     * Check if we are listening to a specific event
     *
     * @param   string  The event name
     * @return  boolean	TRUE if we are listening for a specific event, otherwise FALSE.
     */
    public function hasListeners($name)
    {
        $result = false;
        if(isset($this->_listeners[$name])) {
             $result = (boolean) count($this->_listeners[$name]);
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
    public function setEventPriority($name, KObjectHandable $listener, $priority)
    {
        if(isset($this->_listeners[$name])) {
            $this->_listeners[$name]->setPriority($listener, $priority);
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
    public function getEventPriority($name, KObjectHandable $listener)
    {
        $result = false;

        if(isset($this->_listeners[$name])) {
            $result = $this->_listeners[$name]->getPriority($listener);
        }

        return $result;
    }
}