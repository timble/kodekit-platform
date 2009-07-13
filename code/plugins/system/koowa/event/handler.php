<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Event
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPL <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.koowa.org
 */

/**
 * Class to handle events.
 *
 * @author 		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package 	Koowa_Event
 */
class KEventHandler extends KObject implements KPatternObserver
{
	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 */
	public function __construct(array $options = array())
	{
        $this->identifier = $options['identifier'];
	}

	/**
	 * Method to trigger events
	 *
	 * @param  object	$args	 The event arguments
	 * @return mixed Routine return value
	 */
	public function onNotify(ArrayObject $args)
	{		
		if (method_exists($this, $args['event'])) {
			return $this->{$args['event']}($args);
		} 
		
		return null;
	}
}