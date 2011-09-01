<?php
/**
 * @version     $Id: event.php 786 2011-07-14 01:09:23Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Event Dispatcher Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 */
class ComDebugProfilerEvents extends KEventDispatcher
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
    protected $_events;
 	
 	/**
     * Constructor.
     *
     * @param	object  An optional KConfig object with configuration options
     */
    public function __construct( KConfig $config = null) 
    {          
        parent::__construct($config);
        
        $this->_start = $config->start;
        
        KFactory::get('com://admin/debug.profiler.queries', array('dispatcher' => $this));
    }
    
	/**
     * Force creation of a singleton
     *
     * @return ComDebugProfilerEvents
     */
    public static function instantiate($config = array())
    {
        static $instance;
        
        if ($instance === NULL) {
            $instance = new self($config);
        }
        
        return $instance;
    }
    
	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return void
	 */
    protected function _initialize(KConfig $config)
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
        return $this->_events;    
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
     * @param   object|array   An array, a KConfig or a KEvent object 
     * @return  KEventDispatcher
     */
    public function dispatchEvent($name, $event = array())
    {
        $this->_events[] = array(
        	'message' => $name,
            'time'    => $this->getElapsedTime(),
            'memory'  => $this->getMemory(),
            'caller'  => $event->caller->getIdentifier()
        );  
        
        return parent::dispatchEvent($name, $event);
    }
}