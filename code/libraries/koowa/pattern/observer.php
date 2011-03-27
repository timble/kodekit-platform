<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Pattern
 * @subpackage	Observer
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Observer interface that implements the observer design pattern
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package     Koowa_Pattern
 * @subpackage  Observer
 */
interface KPatternObserver extends KObjectHandlable
{
	/**
	 * Event received in case the observables states has changed
	 *
	 * @param	object	An associative array of arguments
	 * @return mixed
	 */
	public function update(KConfig $args);
}