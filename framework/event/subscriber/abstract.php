<?php
/**
 * @package     Koowa_Event
 * @subpackage 	Subscriber
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Abstract Event Subscriber Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Event
 * @subpackage 	Subscriber
 */
abstract class EventSubscriberAbstract extends Object implements EventSubscriberInterface
{
 	/**
     * List of subscribed events
     *
     * @var array
     */
    private $__subscriptions;
    
    /**
     * The event priority
     *
     * @var int
     */
    protected $_priority;
    
	/**
	 * Constructor.
	 *
	 * @param 	object 	An optional Config object with configuration options.
	 */
	public function __construct(Config $config)
	{
		parent::__construct($config);
		
		$this->_priority = $config->priority;
	}
 	
 	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional Config object with configuration options.
     * @return 	void
     */
    protected function _initialize(Config $config)
    {
        $config->append(array(
    		'priority' => Event::PRIORITY_NORMAL
        ));

        parent::_initialize($config);
    }
    
    /**
     * Get the priority of the handler
     *
     * @return	integer The event priority
     */
    public function getPriority()
    {
        return $this->_priority;
    }
        
    /**
     * Get a list of subscribed events 
     *       
     * Event handlers always start with 'on' and need to be public methods
     * 
     * @return array An array of public methods
     */
    public function getSubscriptions()
    {
        if(!$this->__subscriptions)
        {
            $subscriptions  = array();
            
            //Get all the public methods
            $reflection = new \ReflectionClass($this);
            foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method)
            {
                if(substr($method->name, 0, 2) == 'on') {
                    $subscriptions[$method->name] = array($this, $method->name);
                }
            }
            
            $this->__subscriptions = $subscriptions;
        }
          
        return $this->__subscriptions;
    }
}