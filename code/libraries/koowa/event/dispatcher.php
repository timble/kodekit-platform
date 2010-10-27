<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Event
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Class to handle dispatching of events.
 *
 * @author 		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package 	Koowa_Event
 */
class KEventDispatcher extends KPatternObservable
{
	/**
	 * Add an event listener
	 *
	 * @param	object	An event handler object instance
	 */
	public function addListener(KEventListener $listener)
	{
		return $this->attach($listener);
	}

	/**
	 * Remove an event listener
	 *
	 * @param	object	An event handler object instance
	 * @return 	boolean True if the observer object was detached
	 */
	public function removeListener(KEventListener $listener)
	{
		return $this->detach($listener);
	}
	
	/**
	 * Dispatches an event by dispatching arguments to all observers that handle
	 * the event and returning their return values.
	 *
	 * @param	string			The event name
	 * @param	array|object	An associative array of arguments or a KConfig object
	 * @return	array			An array of results from each function call
	 */
	public function dispatchEvent($event, $args = null)
	{
		if(!($args instanceof KConfig)) {
			$args = new KConfig($args);
		}
		
		$args->event = $event;
		return $this->notify($args);
	}
}
