<?php
/**
* @version		$Id: framework.php 14401 2010-01-26 14:10:00Z louis $
* @package		Joomla
* @subpackage	Installation
* @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

error_reporting( E_ALL );
ini_set('magic_quotes_runtime', 0);

//Installation check, and check on removal of the install directory.
if (file_exists( JPATH_CONFIGURATION.'/configuration.php' ) && (filesize( JPATH_CONFIGURATION.'/configuration.php' ) > 10) && !file_exists( JPATH_INSTALLATION.'/index.php' )) {
	header( 'Location: ../index.php' );
	exit();
}

// System includes
require_once( JPATH_LIBRARIES.'/joomla/import.php');

// Installation file includes
define( 'JPATH_INCLUDES', dirname(__FILE__) );

// Joomla! library imports
jimport( 'joomla.database.table' );
jimport( 'joomla.user.user');
jimport( 'joomla.environment.uri' );
jimport( 'joomla.user.user');
jimport( 'joomla.html.parameter' );
jimport( 'joomla.utilities.utility' );
jimport( 'joomla.language.language');
jimport( 'joomla.utilities.string' );

// Koowa : setup loader
JLoader::import('libraries.koowa.koowa'        , JPATH_ROOT);
JLoader::import('libraries.koowa.loader.loader', JPATH_ROOT);
		
KLoader::addAdapter(new KLoaderAdapterKoowa(Koowa::getPath()));
KLoader::addAdapter(new KLoaderAdapterJoomla(JPATH_LIBRARIES));
KLoader::addAdapter(new KLoaderAdapterComponent(JPATH_BASE));
		
// Koowa : setup factory
KFactory::addAdapter(new KFactoryAdapterKoowa());
KFactory::addAdapter(new KFactoryAdapterJoomla());
KFactory::addAdapter(new KFactoryAdapterComponent());
		
//Koowa : register identifier application paths
KIdentifier::registerApplication('site' , JPATH_SITE);
KIdentifier::registerApplication('admin', JPATH_ADMINISTRATOR);