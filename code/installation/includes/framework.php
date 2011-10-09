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

// Joomla : setup
require_once( JPATH_LIBRARIES.'/joomla/import.php');
jimport( 'joomla.database.table' );
jimport( 'joomla.user.user');
jimport( 'joomla.environment.uri' );
jimport( 'joomla.user.user');
jimport( 'joomla.html.parameter' );
jimport( 'joomla.utilities.utility' );
jimport( 'joomla.language.language');
jimport( 'joomla.utilities.string' );

define( 'JPATH_INCLUDES', dirname(__FILE__) );

// Koowa : setup
require_once( JPATH_LIBRARIES.'/koowa/koowa.php');
Koowa::getInstance();

KLoader::addAdapter(new KLoaderAdapterComponent(array('basepath' => JPATH_BASE)));

KServiceIdentifier::addLocator(KService::get('koowa:service.locator.component'));
		
KServiceIdentifier::setApplication('site' , JPATH_SITE);
KServiceIdentifier::setApplication('admin', JPATH_ADMINISTRATOR);