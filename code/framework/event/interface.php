<?php
/**
 * @version     $Id$
 * @package     Koowa_Event
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Event Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Event
 */
interface KEvent
{
    /**
     * Get the event name
     *
     * @return string	The event name
     */
    public function getName();
    
    /**
     * Set the event name
     *
     * @param string	The event name
     * @return KEvent
     */
    public function setName($name);
    
    /**
     * Get the event target
     *
     * @return object	The event target
     */
    public function getTarget();
    
    /**
     * Set the event target
     *
     * @param object	The event target
     * @return KEvent
     */
    public function setTarget(KObjectServiceable $target);
    
    /**
     * Stores the EventDispatcher that dispatches this Event
     *
     * @param EventDispatcher $dispatcher
     * @return KEvent
     */
    public function setDispatcher(KEventDispatcherInterface $dispatcher);
    
    /**
     * Returns the EventDispatcher that dispatches this Event
     *
     * @return KEventDispatcher
     */
    public function getDispatcher();
    
    /**
     * Returns whether further event listeners should be triggered.
     *
     * @return boolean 	TRUE if the event can propagate. Otherwise FALSE
     */
    public function canPropagate();

    /**
     * Stops the propagation of the event to further event listeners.
     *
     * If multiple event listeners are connected to the same event, no
     * further event listener will be triggered once any trigger calls
     * stopPropagation().
     * 
     * @return KEvent
     */
    public function stopPropagation();
}