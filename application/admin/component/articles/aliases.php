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

KServiceManager::setAlias('com://admin/articles.model.categories', 'com://admin/categories.model.categories');
KServiceManager::setAlias('com://admin/articles.model.terms', 'com://admin/terms.model.terms');
KServiceManager::setAlias('com://admin/articles.view.attachment.file', 'com://admin/attachments.view.attachment.file');