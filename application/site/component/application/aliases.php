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

KServiceManager::setAlias('application'           , 'com://site/application.dispatcher');
KServiceManager::setAlias('application.components', 'com://admin/application.database.rowset.components');
KServiceManager::setAlias('application.languages' , 'com://admin/application.database.rowset.languages');
KServiceManager::setAlias('application.pages'     , 'com://site/application.database.rowset.pages');
KServiceManager::setAlias('application.modules'   , 'com://site/application.database.rowset.modules');

KServiceManager::setAlias('lib://nooku/database.adapter.mysql', 'com://admin/application.database.adapter.mysql');
