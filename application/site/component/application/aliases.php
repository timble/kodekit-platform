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

ObjectManager::setAlias('application'           , 'com:application.dispatcher');
ObjectManager::setAlias('application.components', 'com:application.database.rowset.components');
ObjectManager::setAlias('application.languages' , 'com:application.database.rowset.languages');
ObjectManager::setAlias('application.pages'     , 'com:application.database.rowset.pages');
ObjectManager::setAlias('application.modules'   , 'com:application.database.rowset.modules');

ObjectManager::setAlias('lib:database.adapter.mysql', 'com:application.database.adapter.mysql');
