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
 * Passwords Database Table Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersDatabaseTablePasswords extends KDatabaseTableDefault
{
    protected function _initialize(KConfig $config) {
        $config->append(array('behaviors' => array('expirable')));
        parent::_initialize($config);
    }
}
