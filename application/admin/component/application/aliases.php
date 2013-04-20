<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Object Aliases
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */

use Nooku\Library\ObjectManager;

ObjectManager::getInstance()->setAlias('application'           , 'com:application.dispatcher');
ObjectManager::getInstance()->setAlias('application.components', 'com:application.database.rowset.components');
ObjectManager::getInstance()->setAlias('application.languages' , 'com:application.database.rowset.languages');
ObjectManager::getInstance()->setAlias('application.pages'     , 'com:application.database.rowset.pages');
ObjectManager::getInstance()->setAlias('application.modules'   , 'com:application.database.rowset.modules');

ObjectManager::getInstance()->setAlias('lib:database.adapter.mysql', 'com:application.database.adapter.mysql');
