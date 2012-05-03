<?php
/**
 * @version     $Id$
 * @package     Koowa_Event
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Class to handle events.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Event
 */
class KEventListener extends KObject
{
 	/**
     * List of event handlers
     *
     * @var array
     */
    private $__event_handlers;

    /**
     * The event priority
     *
     * @var int
     */
    protected $_priority;

	/**
	 * Constructor.
	 *
	 * @param 	object 	An optional KConfig object with configuration options.
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		if($config->auto_connect)
		{
		    if(!($config->dispatcher instanceof KEventDispatcher)) {
		        $config->dispatcher = $this->getService($config->dispatcher);
		    }

		    $this->connect($config->dispatcher);
		}
	}

 	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
        	'dispatcher'   => 'koowa:event.dispatcher',
    	    'auto_connect' => true,
    		'priority'     => KCommand::PRIORITY_NORMAL
        ));

        parent::_initialize($config);
    }

    /**
     * Get the event handlers of the listener
     *
     * Event handlers always start with 'on' and need to be public methods
     *
     * @return array An array of public methods
     */
    public function getEventHandlers()
    {
        if(!$this->__event_handlers)
        {
            $handlers  = array();

            //Get all the public methods
            $reflection = new ReflectionClass($this);
            foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method)
            {
                if(substr($method->name, 0, 2) == 'on') {
                    $handlers[] = $method->name;
                }
            }

            $this->__event_handlers = $handlers;
        }

        return $this->__event_handlers;
    }

    /**
     * Connect to an event dispatcher
     *
     * @param  object	The event dispatcher to connect too
     * @return KEventListener
     */
    public function connect(KEventDispatcher $dispatcher)
    {
        $handlers = $this->getEventHandlers();

        foreach($handlers as $handler) {
            $dispatcher->addEventListener($handler, $this, $this->_priority);
        }

        return $this;
    }

	/**
     * Disconnect from an event dispatcher
     *
     * @param  object	The event dispatcher to disconnect from
     * @return KEventListener
     */
    public function disconnect(KEventDispatcher $dispatcher)
    {
        $handlers = $this->getEventHandlers();

        foreach($handlers as $handler) {
            $dispatcher->removeEventListener($handler, $this);
        }

        return $this;
    }
}