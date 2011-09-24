<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Database Event Class
 * 
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 */
 
class ComDebugProfilerQueries extends KEventListener implements KObjectInstantiatable
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
    protected $_queries = array();
    
	/**
     * Force creation of a singleton
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @param 	object	A KFactoryInterface object
     * @return ComDebugProfilerEvents
     */
    public static function getInstance(KConfigInterface $config, KFactoryInterface $factory)
    {
        if (!$factory->has($config->identifier)) 
        {
            $instance = new self($config);
            $factory->set($config->identifier, $instance);
        }
        
        return $factory->get($config->identifier);
    }
    
    /**
     * Get queries
     *
     * @return array Array of the executed database queries
     */
    public function getQueries()
    {
        return $this->_queries;
    }
    
    public function onBeforeDatabaseSelect(KEvent $event) 
    {
        $this->_start = microtime(true);
    }
        
    public function onAfterDatabaseSelect(KEvent $event)
    {
        $event->time = microtime(true) - $this->_start;
        $this->_queries[] = $event;
    }
    
    public function onBeforeDatabaseUpdate(KEvent $event)
    {
        $this->_start = microtime(true);
    }
        
    public function onAfterDatabaseUpdate(KEvent $event)
    {
        $event->time = microtime(true) - $this->_start;
        $this->_queries[] = $event;
    }
    
    public function onBeforeDatabaseInsert(KEvent $event)
    {
        $this->_start = microtime(true);
    }
                
    public function onAfterDatabaseInsert(KEvent $event)
    {
        $event->time = microtime(true) - $this->_start;
        $this->_queries[] = $event;
    }
    
    public function onBeforeDatabaseDelete(KEvent $event)
    {
        $this->_start = microtime(true);
    }
                
    public function onAfterDatabaseDelete(KEvent $event)
    {
        $event->time = microtime(true) - $this->_start;
        $this->_queries[] = $event;
    }
    
    public function onBeforeDatabaseShow(KEvent $event)
    {
        $this->_start = microtime(true);
    }
            
    public function onAfterDatabaseShow(KEvent $event)
    {
        $event->time = microtime(true) - $this->_start;
        $this->_queries[] = $event;
    }
}