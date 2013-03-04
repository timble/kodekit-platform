<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

/**
 * Database Event Subscriber
 * 
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Debug
 */
class ComDebugEventSubscriberDatabase extends KEventSubscriberAbstract implements KServiceInstantiatable
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
     * @param 	KConfigInterface            $config	  A KConfig object with configuration options
     * @param 	KServiceManagerInterface	$manager  A KServiceInterface object
     * @return ComDebugProfilerEvents
     */
    public static function getInstance(KConfigInterface $config, KServiceManagerInterface $manager)
    {
        if (!$manager->has($config->service_identifier))
        {
            $instance = new self($config);
            $manager->set($config->service_identifier, $instance);
        }
        
        return $manager->get($config->service_identifier);
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
}