<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

jimport( 'joomla.error.profiler' );

/**
 * Debug Event Dispatcher
 * 
 * Specialised event dispatcher
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 */
class ComDebugEventDispatcher extends KEventDispatcher
{
	/**
	 * The database queries
	 * 
	 * @var array
	 */
    protected static $_queries = array();
    
    /**
     * Get the list of queries
     * 
     * @return 	array 	A list of the executed queries 
     */
    public function getQueries()
    {
        return self::$_queries;
    }
	
	/**
     * Dispatches an event by dispatching arguments to all listeners that handle
     * the event and returning their return values.
     * 
     * This function will add a mark to the profiler for each event dispatched and
     * will also capture all the database queries that are being executed.
     *
     * @param   string  The event name
     * @param   object|array   An array, a KConfig or a KEvent object 
     * @return  KEventDispatcher
     */
    public function dispatchEvent($name, $event = array())
    {
        if($event instanceof KCommandContext) 
        {
            if($event->caller instanceof KDatabaseAdapterInterface && !empty($event->query)) {
                self::$_queries[] = $event;
            }
        }
        
        JProfiler::getInstance('Application')->mark( $name );
        
        return parent::dispatchEvent($name, $event);
    }
}