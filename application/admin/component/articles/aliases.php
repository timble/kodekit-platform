<?php
/**
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Service Aliases
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Contacts
 */

use Nooku\Library\ServiceManager;

ServiceManager::setAlias('com:articles.model.terms', 'com:terms.model.terms');
ServiceManager::setAlias('com:articles.model.categories', 'com:categories.model.categories');
ServiceManager::setAlias('com:articles.view.attachment.file', 'com:attachments.view.attachment.file');