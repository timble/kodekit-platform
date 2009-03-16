<?php
/**
 * @version		$Id:proxy.php 46 2008-03-01 18:39:32Z mjaz $
 * @category	Koowa
 * @package		Koowa_Pattern
 * @subpackage	Observer
 * @copyright	Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Abstract observer class to implement the observer design pattern
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Pattern
 * @subpackage  Observer
 */
interface KPatternObserver
{
	/**
	 * Event received in case the observables states has changed
	 *
	 * @param	object	$args	An associative array of arguments
	 * @return mixed
	 */
	public function onNotify(ArrayObject $args);
	
	/**
	 * This function returns an unique identifier for the object. This id can be used as 
	 * a hash key for storing objects or for identifying an object
	 * 
	 * @return string A string that is unique
	 */
	public function getHandle();
}