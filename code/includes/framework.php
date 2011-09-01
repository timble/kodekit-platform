<?php
/**
 * @version     $Id: sections.php 592 2011-03-16 00:30:35Z johanjanssens $
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

// System includes
require_once( JPATH_LIBRARIES.'/joomla/import.php');

// Joomla : import libraries
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

// Koowa : setup loader
JLoader::import('libraries.koowa.koowa'        , JPATH_ROOT);
JLoader::import('libraries.koowa.loader.loader', JPATH_ROOT);
		
KLoader::addAdapter(new KLoaderAdapterKoowa(Koowa::getPath()));
KLoader::addAdapter(new KLoaderAdapterJoomla(JPATH_LIBRARIES));
KLoader::addAdapter(new KLoaderAdapterModule(JPATH_BASE));
KLoader::addAdapter(new KLoaderAdapterPlugin(JPATH_ROOT));
KLoader::addAdapter(new KLoaderAdapterComponent(JPATH_BASE));
		
// Koowa : setup factory
KFactory::addAdapter(new KFactoryAdapterKoowa());
KFactory::addAdapter(new KFactoryAdapterJoomla());
KFactory::addAdapter(new KFactoryAdapterModule());
KFactory::addAdapter(new KFactoryAdapterPlugin());
KFactory::addAdapter(new KFactoryAdapterComponent());
		
//Koowa : register identifier application paths
KIdentifier::registerApplication('site' , JPATH_SITE);
KIdentifier::registerApplication('admin', JPATH_ADMINISTRATOR);

//Koowa : setup factory mappings
KFactory::map('koowa:database.adapter.mysqli', 'com://admin/default.database.adapter.mysqli');