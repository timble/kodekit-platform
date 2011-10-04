<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

ini_set('magic_quotes_runtime', 0);

//Installation check, and check on removal of the install directory.
if (!file_exists( JPATH_CONFIGURATION.'/configuration.php' ) || (filesize( JPATH_CONFIGURATION.'/configuration.php' ) < 10) /*|| file_exists( JPATH_INSTALLATION . DS . 'index.php' )*/) 
{
	if( file_exists( JPATH_INSTALLATION.'/index.php' ) ) {
		header( 'Location: installation/index.php' );
		exit();
	} else {
		echo 'No configuration file found and no installation code available. Exiting...';
		exit();
	}
}

// Joomla : setup
require_once( JPATH_LIBRARIES.'/joomla/import.php');
jimport( 'joomla.application.menu' );
jimport( 'joomla.user.user');
jimport( 'joomla.environment.uri' );
jimport( 'joomla.html.html' );
jimport( 'joomla.html.parameter' );
jimport( 'joomla.utilities.utility' );
jimport( 'joomla.event.event');
jimport( 'joomla.event.dispatcher');
jimport( 'joomla.language.language');
jimport( 'joomla.utilities.string' );
jimport( 'joomla.plugin.helper' );

// Koowa : setup
require_once JPATH_CONFIGURATION.'/configuration.php';
$config = new JConfig();

require_once( JPATH_LIBRARIES.'/koowa/koowa.php');
Koowa::getInstance(array(
	'cache_prefix'  => md5($config->secret).'-cache-koowa',
	'cache_enabled' => $config->caching
));	

unset($config);

KLoader::addAdapter(new KLoaderAdapterModule(array('basepath' => JPATH_BASE)));
KLoader::addAdapter(new KLoaderAdapterPlugin(array('basepath' => JPATH_ROOT)));
KLoader::addAdapter(new KLoaderAdapterComponent(array('basepath' => JPATH_BASE)));

KServiceIdentifier::addLocator(KService::get('koowa:service.locator.module'));
KServiceIdentifier::addLocator(KService::get('koowa:service.locator.plugin'));
KServiceIdentifier::addLocator(KService::get('koowa:service.locator.component'));
		
KServiceIdentifier::setApplication('site' , JPATH_SITE);
KServiceIdentifier::setApplication('admin', JPATH_ADMINISTRATOR);

KService::setAlias('koowa:database.adapter.mysqli', 'com://admin/default.database.adapter.mysqli');