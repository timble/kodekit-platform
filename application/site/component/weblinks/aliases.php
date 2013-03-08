<?php
/**
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Service Aliases
 *
 * @author    	Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */

use Nooku\Framework\ServiceManager;

ServiceManager::setAlias('com://site/weblinks.model.categories', 'com://admin/categories.model.categories');
ServiceManager::setAlias('com://site/weblinks.model.weblinks'  , 'com://admin/weblinks.model.weblinks');