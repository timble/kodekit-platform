<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Service Aliases
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */

use Nooku\Framework\ServiceManager;

ServiceManager::setAlias('com://site/users.controller.default'   , 'com://admin/users.controller.default');
ServiceManager::setAlias('com://site/users.model.users'          , 'com://admin/users.model.users');
ServiceManager::setAlias('com://site/users.model.sessions'       , 'com://admin/users.model.sessions');
ServiceManager::setAlias('com://site/users.database.row.user'    , 'com://admin/users.database.row.user');
ServiceManager::setAlias('com://site/users.database.row.session' , 'com://admin/users.database.row.session');
ServiceManager::setAlias('com://site/users.database.table.groups', 'com://admin/users.database.table.groups');
erviceManager::setAlias('com://site/users.database.table.users' , 'com://admin/users.database.table.users');
ServiceManager::setAlias('com://site/users.helper.password'      , 'com://admin/users.helper.password');
ServiceManager::setAlias('com://site/users.model.passwords'      , 'com://admin/users.model.passwords');