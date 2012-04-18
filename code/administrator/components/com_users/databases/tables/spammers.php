<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Spammers Database Table Class
 *
 * @author 		Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category 	Nooku
 * @package 	Nooku_Server
 * @subpackage  Users
 */
class ComUsersDatabaseTableSpammers extends KDatabaseTableDefault
{
	
	protected function _initialize(KConfig $config)
	{
		$config->append(array('behaviors' => array('creatable')));
		parent::_initialize($config);
	}

}