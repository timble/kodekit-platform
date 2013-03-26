<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Debug;

use Nooku\Framework;

/**
 * Database Event Subscriber
 * 
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Debug
 */
class EventSubscriberDatabase extends Framework\EventSubscriberAbstract implements Framework\ServiceInstantiatable
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
     * @param 	Framework\Config                     $config	  A Framework\Config object with configuration options
     * @param 	Framework\ServiceManagerInterface	$manager  A KServiceInterface object
     * @return  ProfilerEvents
     */
    public static function getInstance(Framework\Config $config, Framework\ServiceManagerInterface $manager)
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
    
    public function onBeforeDatabaseSelect(Framework\Event $event)
    {
        $this->_start = microtime(true);
    }
        
    public function onAfterDatabaseSelect(Framework\Event $event)
    {
        $event->time = microtime(true) - $this->_start;
        $this->_queries[] = $event;
    }
    
    public function onBeforeDatabaseUpdate(Framework\Event $event)
    {
        $this->_start = microtime(true);
    }
        
    public function onAfterDatabaseUpdate(Framework\Event $event)
    {
        $event->time = microtime(true) - $this->_start;
        $this->_queries[] = $event;
    }
    
    public function onBeforeDatabaseInsert(Framework\Event $event)
    {
        $this->_start = microtime(true);
    }
                
    public function onAfterDatabaseInsert(Framework\Event $event)
    {
        $event->time = microtime(true) - $this->_start;
        $this->_queries[] = $event;
    }
    
    public function onBeforeDatabaseDelete(Framework\Event $event)
    {
        $this->_start = microtime(true);
    }
                
    public function onAfterDatabaseDelete(Framework\Event $event)
    {
        $event->time = microtime(true) - $this->_start;
        $this->_queries[] = $event;
    }
}