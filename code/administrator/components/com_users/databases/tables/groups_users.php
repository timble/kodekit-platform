<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Users
 * @copyright      Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Groups Users Database Table Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersDatabaseTableGroups_users extends KDatabaseTableDefault
{
    protected function _initialize(KConfig $config) {
        $config->append(array('column_map' => array('group_id' => 'users_group_id', 'user_id' => 'users_user_id')));
        parent::_initialize($config);
    }
}