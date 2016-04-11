<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

/**
 * Framework loader
 *
 * @author Johan Janssens <http://github.com/johanjanssens>
 */

use Kodekit\Library;

//Don't run in STRICT mode (Joomla is not E_STRICT compat)
error_reporting(error_reporting() & ~ E_STRICT);

define( 'DS', DIRECTORY_SEPARATOR );

define('APPLICATION_NAME' , 'site');
define('APPLICATION_ROOT' , realpath($_SERVER['DOCUMENT_ROOT']));
define('APPLICATION_BASE' , APPLICATION_ROOT.'/application/site');

// Bootstrap the framework
$config = require APPLICATION_ROOT.'/config/bootstrapper.php';

require_once(APPLICATION_ROOT.'/library/code/kodekit.php');
$kodekit = Kodekit::getInstance(array(
    'debug'     =>  $config['debug'],
    'cache'     =>  $config['cache'],
    'base_path' =>  APPLICATION_BASE
));

//Bootstrap the application
Kodekit::getObject('object.bootstrapper')
    ->registerComponents($kodekit->getRootPath().'/application/site/component')
    ->registerComponents($kodekit->getRootPath().'/component')
    ->registerFile($kodekit->getRootPath(). '/config/bootstrapper.php')
    ->bootstrap();

// Bootstrap Joomla
require_once($kodekit->getVendorPath() . '/joomla/import.php');
jimport('joomla.environment.uri');
jimport('joomla.html.html');
jimport('joomla.html.parameter');
jimport('joomla.utilities.utility');
