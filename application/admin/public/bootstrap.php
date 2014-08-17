<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

/**
 * Framework loader
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 */

use Nooku\Library;

//Don't run in STRICT mode (Joomla is not E_STRICT compat)
error_reporting(error_reporting() | ~ E_STRICT);

// Bootstrap the framework
$config = require JPATH_ROOT . '/config/bootstrapper.php';

require_once(JPATH_ROOT . '/library/nooku.php');
$nooku = Nooku::getInstance(array(
    'debug'           =>  isset($config['debug']) ? (bool) $config['debug'] : false,
    'cache_namespace' => 'admin',
    'cache_enabled'   =>  isset($config['caching']) ? (bool) $config['debug'] : false,
    'base_path'       =>  JPATH_ROOT.'/application/admin'
));

//Bootstrap the application
Library\ObjectManager::getInstance()->getObject('object.bootstrapper')
    ->registerApplication('site' , $nooku->getRootPath().'/application/site/component')
    ->registerApplication('admin', $nooku->getRootPath().'/application/admin/component', true)
    ->registerComponents($nooku->getRootPath().'/component', 'nooku')
    ->registerFile($nooku->getRootPath(). '/config/bootstrapper.php')
    ->bootstrap();

// Joomla : setup
require_once($nooku->getVendorPath() . '/joomla/import.php');
jimport('joomla.environment.uri');
jimport('joomla.html.html');
jimport('joomla.html.parameter');
jimport('joomla.utilities.utility');
jimport('joomla.language.language');
