<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Framework loader
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 */

//Installation check
if (!file_exists(JPATH_SITES . '/config.php') || (filesize(JPATH_SITES . '/config.php') < 10)) {
    echo 'No configuration file found. Exciting...';
    exit();
}

// Joomla : setup
require_once(JPATH_VENDOR . '/joomla/import.php');
jimport('joomla.environment.uri');
jimport('joomla.html.html');
jimport('joomla.html.parameter');
jimport('joomla.utilities.utility');
jimport('joomla.language.language');

// Koowa : setup
require_once JPATH_SITES . '/config.php';
$config = new JConfig();

require_once(JPATH_ROOT . '/framework/koowa.php');
Koowa::getInstance(array(
    'cache_prefix' => md5($config->secret) . '-cache-koowa',
    'cache_enabled' => $config->caching
));

unset($config);

KService::get('loader')->addAdapter(new KLoaderAdapterComponent(array('basepath' => JPATH_APPLICATION)));
KServiceIdentifier::addLocator(KService::get('koowa:service.locator.component'));

KServiceIdentifier::setApplication('site', JPATH_ROOT . '/site');
KServiceIdentifier::setApplication('admin', JPATH_ROOT . '/administrator');