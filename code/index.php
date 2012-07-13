<?php
/**
 * @version     $Id: dispatcher.php 4629 2012-05-06 22:11:00Z johanjanssens $
 * @package     Nooku_Server
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

// Set flag that this is a parent file
define( '_JEXEC', 1 );

define('JPATH_BASE'         , dirname(__FILE__) );
define('JPATH_ROOT'         , JPATH_BASE);
define('JPATH_SITE'         , JPATH_ROOT );
define('JPATH_ADMINISTRATOR', JPATH_ROOT.'/administrator' );
define('JPATH_LIBRARIES'    , JPATH_ROOT.'/libraries' );
define('JPATH_PLUGINS'      , JPATH_ROOT.'/plugins'   );
define('JPATH_THEMES'       , JPATH_BASE.'/templates' );
define('JPATH_SITES'        , JPATH_ROOT.'/sites');

define( 'DS', DIRECTORY_SEPARATOR );

require_once(JPATH_BASE.'/includes/framework.php' );

//Nooku Server identification information
header('X-Nooku-Server: version='.Koowa::VERSION);

KLoader::loadIdentifier('com://site/application.aliases');

echo KService::get('com://site/application.dispatcher')->run();
