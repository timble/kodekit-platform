<?php
/**
 * @version 	$Id$
 * @category	Koowa
 * @package		Koowa_Database
 * @subpackage 	Behavior
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Database Hittable Behavior
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage 	Behavior
 */
class KDatabaseBehaviorHittable extends KDatabaseBehaviorAbstract
{
	/**
     * Increase hit counter by 1
     *
     * Requires a hits field to be present in the table
     */
	public function hit()
	{
		$this->hits++;
		$this->save();

		return $this->_mixer;
	}
}