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
 * Factory Mappings
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */

KFactory::map('site::com.users.controller.default'   , 'admin::com.users.controller.default');
KFactory::map('site::com.users.model.users'          , 'admin::com.users.model.users');
KFactory::map('site::com.users.database.row.user'    , 'admin::com.users.database.row.user');
KFactory::map('site::com.users.database.table.groups', 'admin::com.users.database.table.groups');
KFactory::map('site::com.users.database.table.users' , 'admin::com.users.database.table.users');
KFactory::map('site::com.users.helper.password'      , 'admin::com.users.helper.password');