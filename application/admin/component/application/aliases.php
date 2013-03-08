<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Service Aliases
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */

use Nooku\Framework\ServiceManager;

ServiceManager::setAlias('application'           , 'com://admin/application.dispatcher');
ServiceManager::setAlias('application.components', 'com://admin/application.database.rowset.components');
ServiceManager::setAlias('application.languages' , 'com://admin/application.database.rowset.languages');
ServiceManager::setAlias('application.pages'     , 'com://admin/application.database.rowset.pages');
ServiceManager::setAlias('application.modules'   , 'com://admin/application.database.rowset.modules');

ServiceManager::setAlias('lib://nooku/database.adapter.mysql', 'com://admin/application.database.adapter.mysql');
