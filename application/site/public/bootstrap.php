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
 * @author Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 */

use Nooku\Library;

//Installation check
if (!file_exists(JPATH_ROOT . '/config/config.php') || (filesize(JPATH_ROOT . '/config/config.php') < 10)) {
    echo 'No configuration file found. Exciting...';
    exit();
}

//Don't run in STRICT mode (Joomla is not E_STRICT compat)
error_reporting(error_reporting() | ~ E_STRICT);

// Joomla : setup
require_once(JPATH_VENDOR.'/joomla/import.php');
jimport('joomla.environment.uri');
jimport('joomla.html.html');
jimport('joomla.html.parameter');
jimport('joomla.utilities.utility');
jimport('joomla.language.language');

// Koowa : setup
require_once JPATH_ROOT . '/config/config.php';
$config = new JConfig();

require_once(JPATH_ROOT . '/library/nooku.php');
\Nooku::getInstance(array(
    'debug'           => $config->debug,
    'cache_namespace' => 'site',
    'cache_enabled'   => $config->caching
));

unset($config);

//Add application basepaths
Library\ClassLoader::getInstance()->registerBasepath('site' , JPATH_ROOT.'/application/site/component', true);
Library\ClassLoader::getInstance()->registerBasepath('admin', JPATH_ROOT.'/application/admin/component');

//Setup the component locator
Library\ClassLoader::getInstance()->getLocator('component')->registerNamespaces(
    array(
        '\\'              => JPATH_APPLICATION.'/component',
        'Nooku\Component' => JPATH_ROOT.'/component'
    )
);

Library\ObjectManager::getInstance()->registerLocator('lib:object.locator.component');

//Bootstrap the components
Library\ObjectManager::getInstance()->getObject('com:application.bootstrapper', array(
    'directory' => JPATH_APPLICATION.'/component'
))->bootstrap();
