<?php
/**
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright	Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Service Aliases
 *
 * @author      Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package     Nooku_Server
 * @subpackage  Articles
 */

use Nooku\Framework\ServiceManager;

ServiceManager::setAlias('com://site/articles.database.table.articles'  , 'com://admin/articles.database.table.articles');

ServiceManager::setAlias('com://site/articles.model.articles'  , 'com://admin/articles.model.articles');
ServiceManager::setAlias('com://site/articles.model.categories', 'com://admin/categories.model.categories');

