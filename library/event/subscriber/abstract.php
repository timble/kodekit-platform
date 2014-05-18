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
 * Abstract Event Subscriber
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Event
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
	 * @param ObjectConfig $config 	An optional ObjectConfig object with configuration options.
	 */
	public function __construct(ObjectConfig $config)
	{
		parent::__construct($config);
		
		$this->_priority = $config->priority;
	}
 	
 	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	ObjectConfig $config 	An optional ObjectConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(ObjectConfig $config)
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