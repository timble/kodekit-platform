<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Debug;

use Nooku\Library;

/**
 * Profiler Event Dispatcher
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Debug
 */
class EventProfiler extends Library\EventDispatcherAbstract
{
   /**
    * The start time
    * 
    * @var int
    */
    protected $_start = 0;
    
    /**
     * Array of profile marks
     *
     * @var array
     */
    protected static $_events;
 	
 	/**
     * Constructor.
     *
     * @param	object  An optional Library\ObjectConfig object with configuration options
     */
    public function __construct(Library\ObjectConfig $config)
    {          
        parent::__construct($config);
        
        $this->_start = $config->start;
    }
    
	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional Library\ObjectConfig object with configuration options
     * @return void
	 */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
        	'start'   => microtime(true),
        ));

       parent::_initialize($config);
    }
    
	/**
     * Get all profiler marks.
     *
     * @return array Array of profiler marks
     */
    public function getEvents() 
    {
        return self::$_events;    
    }
    
	/**
     * Get information about current memory usage.
     *
     * @return int The memory usage
     * @link PHP_MANUAL#memory_get_usage
     */
    public function getMemory()
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
     * Dispatches an event by dispatching arguments to all listeners that handle
     * the event and returning their return values.
     * 
     * This function will add a mark to the profiler for each event dispatched
     *
     * @param   string  The event name
     * @param   object|array   An array, a Library\ObjectConfig or a Library\Event object
     * @return  Library\EventDispatcher
     */
    public function dispatchEvent($name, $event = array())
    {
        self::$_events[] = array(
        	'message' => $name,
            'time'    => $this->getElapsedTime(),
            'memory'  => $this->getMemory(),
            'target'  => $event->getTarget()->getIdentifier()
        );  
        
        return parent::dispatchEvent($name, $event);
    }
}