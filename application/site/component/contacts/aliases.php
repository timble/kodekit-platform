<?php
/**
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Service Aliases
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 */

use Nooku\Framework\ServiceManager;

ServiceManager::setAlias('com://site/contacts.model.categories', 'com://admin/categories.model.categories');
ServiceManager::setAlias('com://site/contacts.model.contacts'  , 'com://admin/contacts.model.contacts');