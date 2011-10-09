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
 * Service Aliases
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */

KService::setAlias('com://site/users.controller.default'   , 'com://admin/users.controller.default');
KService::setAlias('com://site/users.model.users'          , 'com://admin/users.model.users');
KService::setAlias('com://site/users.database.row.user'    , 'com://admin/users.database.row.user');
KService::setAlias('com://site/users.database.table.groups', 'com://admin/users.database.table.groups');
KService::setAlias('com://site/users.database.table.users' , 'com://admin/users.database.table.users');
KService::setAlias('com://site/users.helper.password'      , 'com://admin/users.helper.password');