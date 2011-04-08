<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Groups Database Table Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersDatabaseTableGroups extends KDatabaseTableDefault
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'name'	=> 'core_acl_aro_groups',
			'base'	=> 'core_acl_aro_groups'
		));

		parent::_initialize($config);
	}
}