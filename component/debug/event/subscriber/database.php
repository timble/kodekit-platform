<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Debug;

use Nooku\Library;

/**
 * Database Event Subscriber
 * 
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Debug
 */
class EventSubscriberDatabase extends Library\EventSubscriberAbstract implements Library\ObjectInstantiatable
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
     * @param 	Library\Config                     $config	  A Library\Config object with configuration options
     * @param 	Library\ObjectManagerInterface	$manager      ObjectInterface object
     * @return  ProfilerEvents
     */
    public static function getInstance(Library\Config $config, Library\ObjectManagerInterface $manager)
    {
        if (!$manager->has($config->object_identifier))
        {
            $instance = new self($config);
            $manager->set($config->object_identifier, $instance);
        }
        
        return $manager->get($config->object_identifier);
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
    
    public function onBeforeDatabaseSelect(Library\Event $event)
    {
        $this->_start = microtime(true);
    }
        
    public function onAfterDatabaseSelect(Library\Event $event)
    {
        $event->time = microtime(true) - $this->_start;
        $this->_queries[] = $event;
    }
    
    public function onBeforeDatabaseUpdate(Library\Event $event)
    {
        $this->_start = microtime(true);
    }
        
    public function onAfterDatabaseUpdate(Library\Event $event)
    {
        $event->time = microtime(true) - $this->_start;
        $this->_queries[] = $event;
    }
    
    public function onBeforeDatabaseInsert(Library\Event $event)
    {
        $this->_start = microtime(true);
    }
                
    public function onAfterDatabaseInsert(Library\Event $event)
    {
        $event->time = microtime(true) - $this->_start;
        $this->_queries[] = $event;
    }
    
    public function onBeforeDatabaseDelete(Library\Event $event)
    {
        $this->_start = microtime(true);
    }
                
    public function onAfterDatabaseDelete(Library\Event $event)
    {
        $event->time = microtime(true) - $this->_start;
        $this->_queries[] = $event;
    }
}