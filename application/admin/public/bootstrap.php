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

//Don't run in STRICT mode (Joomla is not E_STRICT compat)
error_reporting(error_reporting() | ~ E_STRICT);

define( 'DS', DIRECTORY_SEPARATOR );

define('APPLICATION_NAME' , 'admin');
define('APPLICATION_ROOT' , realpath($_SERVER['DOCUMENT_ROOT']));
define('APPLICATION_BASE' , APPLICATION_ROOT.'/application/admin');

// Bootstrap the framework
$config = require APPLICATION_ROOT.'/config/bootstrapper.php';

require_once(APPLICATION_ROOT.'/library/nooku.php');
$nooku = Nooku::getInstance(array(
    'debug'           =>  $config['debug'],
    'cache'           =>  $config['cache'],
    'cache_namespace' =>  $config['cache_namespace'],
    'base_path'       =>  APPLICATION_BASE
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
