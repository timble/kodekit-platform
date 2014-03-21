<?php
/**
 * @version     $Id$
 * @package     Koowa_Tests
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// System checks
@set_magic_quotes_runtime( 0 );
@ini_set('zend.ze1_compatibility_mode', '0');

// System includes
require_once( JPATH_LIBRARIES		.'/joomla/import.php');

// Pre-Load configuration
require_once( JPATH_CONFIGURATION	.'/configuration.php' );


//Set the application information
jimport( 'joomla.application.helper' );
$info =& JApplicationHelper::getClientInfo();
$info[4] = new stdClass();
$info[4]->id    = 4;
$info[4]->name  = 'koowapp';
$info[4]->path  = JPATH_KOOWA_APP;

// Joomla! framework loading
//jimport( 'joomla.application.menu' );
jimport( 'joomla.user.user');
//jimport( 'joomla.html.html' );
jimport( 'joomla.utilities.utility' );
//jimport( 'joomla.event.event');
//jimport( 'joomla.event.dispatcher');
//jimport( 'joomla.language.language');
jimport( 'joomla.utilities.string' );
