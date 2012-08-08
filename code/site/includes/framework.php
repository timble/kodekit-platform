<?php
/**
 * @version     $Id: sections.php 592 2011-03-16 00:30:35Z johanjanssens $
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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

ini_set('magic_quotes_runtime', 0);

//Installation check, and check on removal of the install directory.
if (!file_exists( JPATH_ROOT.'/configuration.php' ) || (filesize( JPATH_ROOT.'/configuration.php' ) < 10))
{
	echo 'No configuration file found. Exciting...';
	exit();
}

// System includes
require_once( JPATH_LIBRARIES.'/joomla/import.php');

// Joomla : setup
jimport( 'joomla.user.user');
jimport( 'joomla.environment.uri' );
jimport( 'joomla.html.html' );
jimport( 'joomla.html.parameter' );
jimport( 'joomla.utilities.utility' );
jimport( 'joomla.language.language');
jimport( 'joomla.utilities.string' );

jimport( 'joomla.application.helper');
jimport( 'joomla.application.component.helper');

// Koowa : setup
require_once JPATH_ROOT.'/configuration.php';
$config = new JConfig();

require_once( JPATH_LIBRARIES.'/koowa/koowa.php');
Koowa::getInstance(array(
	'cache_prefix'  => md5($config->secret).'-cache-koowa',
	'cache_enabled' => $config->caching
));	

unset($config);

KLoader::addAdapter(new KLoaderAdapterModule(array('basepath' => JPATH_APPLICATION)));
KLoader::addAdapter(new KLoaderAdapterComponent(array('basepath' => JPATH_APPLICATION)));

KServiceIdentifier::addLocator(KService::get('koowa:service.locator.module'));
KServiceIdentifier::addLocator(KService::get('koowa:service.locator.component'));

KServiceIdentifier::setApplication('site' , JPATH_ROOT.'/site');
KServiceIdentifier::setApplication('admin', JPATH_ROOT.'/administrator');