<?php
/**
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Object Aliases
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Contacts
 */

use Nooku\Library\ObjectManager;

ObjectManager::getInstance()->setAlias('com:articles.model.terms', 'com:terms.model.terms');
ObjectManager::getInstance()->setAlias('com:articles.model.categories', 'com:categories.model.categories');
ObjectManager::getInstance()->setAlias('com:articles.view.attachment.file', 'com:attachments.view.attachment.file');