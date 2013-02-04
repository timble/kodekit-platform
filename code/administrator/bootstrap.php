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
if (!file_exists(JPATH_ROOT . '/configuration.php') || (filesize(JPATH_ROOT . '/configuration.php') < 10)) {
    echo 'No configuration file found. Exciting...';
    exit();
}

//Suhosin compatibility
if(in_array('suhosin', get_loaded_extensions()))
{
    //Attempt setting the whitelist value
    @ini_set('suhosin.executor.include.whitelist', 'tmpl://, file://');

    //Checking if the whitelist is ok
    if(!@ini_get('suhosin.executor.include.whitelist') || strpos(@ini_get('suhosin.executor.include.whitelist'), 'tmpl://') === false)
    {
        throw Exception(sprintf(JText::_('Your server has Suhosin loaded. Please follow <a href="%s" target="_blank">this</a> tutorial.'), 'https://nooku.assembla.com/wiki/show/nooku-framework/Known_Issues'));
        exit();
    }
}

//Safety Extender compatibility
if(extension_loaded('safeex') && strpos('tmpl', ini_get('safeex.url_include_proto_whitelist')) === false)
{
    $whitelist = ini_get('safeex.url_include_proto_whitelist');
    $whitelist = (strlen($whitelist) ? $whitelist . ',' : '') . 'tmpl';
    ini_set('safeex.url_include_proto_whitelist', $whitelist);
}

// Joomla : setup
require_once(JPATH_LIBRARIES . '/joomla/import.php');
jimport('joomla.environment.uri');
jimport('joomla.html.html');
jimport('joomla.html.parameter');
jimport('joomla.utilities.utility');
jimport('joomla.language.language');

// Koowa : setup
require_once JPATH_ROOT . '/configuration.php';
$config = new JConfig();

require_once(JPATH_LIBRARIES . '/koowa/koowa.php');
Koowa::getInstance(array(
    'cache_prefix' => md5($config->secret) . '-cache-koowa',
    'cache_enabled' => $config->caching
));

unset($config);

KService::get('loader')->addAdapter(new KLoaderAdapterComponent(array('basepath' => JPATH_APPLICATION)));
KServiceIdentifier::addLocator(KService::get('koowa:service.locator.component'));

KServiceIdentifier::setApplication('site', JPATH_ROOT . '/site');
KServiceIdentifier::setApplication('admin', JPATH_ROOT . '/administrator');