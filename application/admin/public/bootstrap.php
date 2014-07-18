<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

/**
 * Framework loader
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 */

use Nooku\Library;

//Installation check
if (!file_exists(JPATH_ROOT . '/config/config.php') || (filesize(JPATH_ROOT . '/config/config.php') < 10)) {
    echo 'No configuration file found. Exciting...';
    exit();
}

//Don't run in STRICT mode (Joomla is not E_STRICT compat)
error_reporting(error_reporting() | ~ E_STRICT);

// Koowa : setup
require_once JPATH_ROOT . '/config/config.php';
$config = new JConfig();

require_once(JPATH_ROOT . '/library/nooku.php');
$nooku = Nooku::getInstance(array(
    'debug'           => $config->debug,
    'cache_namespace' => 'admin',
    'cache_enabled'   =>  $config->caching,
    'base_path'       =>  JPATH_ROOT.'/application/admin'
));

unset($config);

//Register application namespaces
Library\ClassLoader::getInstance()->registerNamespace('site' , $nooku->getRootPath().'/application/site/component');
Library\ClassLoader::getInstance()->registerNamespace('admin', $nooku->getRootPath().'/application/admin/component', true);

//Bootstrap the application
Library\ObjectManager::getInstance()->getObject('object.bootstrapper')
    ->registerDirectory($nooku->getRootPath().'/component', 'nooku')
    ->registerDirectory($nooku->getBasePath().'/component')
    ->bootstrap();

// Joomla : setup
require_once($nooku->getVendorPath() . '/joomla/import.php');
jimport('joomla.environment.uri');
jimport('joomla.html.html');
jimport('joomla.html.parameter');
jimport('joomla.utilities.utility');
jimport('joomla.language.language');
