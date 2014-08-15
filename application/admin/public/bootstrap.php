<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

/**
 * Framework loader
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
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

//Bootstrap the application
Library\ObjectManager::getInstance()->getObject('object.bootstrapper')
    ->registerApplication('site' , $nooku->getRootPath().'/application/site/component')
    ->registerApplication('admin', $nooku->getRootPath().'/application/admin/component', true)
    ->registerComponents($nooku->getRootPath().'/component', 'nooku')
    ->bootstrap();

// Joomla : setup
require_once($nooku->getVendorPath() . '/joomla/import.php');
jimport('joomla.environment.uri');
jimport('joomla.html.html');
jimport('joomla.html.parameter');
jimport('joomla.utilities.utility');
jimport('joomla.language.language');
