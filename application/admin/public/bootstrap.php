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

// Joomla : setup
require_once(JPATH_VENDOR . '/joomla/import.php');
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
    'cache_prefix' => md5($config->secret) . '-cache-koowa',
    'cache_enabled' => $config->caching
));

unset($config);

//Setup the component locator
Library\ClassLoader::getInstance()->getLocator('com')->registerNamespaces(
    array(
        '\\'              => JPATH_APPLICATION.'/component',
        'Nooku\Component' => JPATH_ROOT.'/component'
    )
);

//Add the different applications
Library\ClassLoader::getInstance()->addApplication('site' , JPATH_ROOT.'/application/site');
Library\ClassLoader::getInstance()->addApplication('admin', JPATH_ROOT.'/application/admin');

//Bootstrap the components
Library\ObjectManager::getInstance()->getObject('lib:bootstrapper.application', array(
    'directory' => JPATH_APPLICATION.'/component'
))->bootstrap();