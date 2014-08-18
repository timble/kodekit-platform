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


//Defines
define( 'JPATH_CONFIGURATION', 	JPATH_ROOT );
define( 'JPATH_ADMINISTRATOR', 	JPATH_ROOT.DS.'administrator' );
define( 'JPATH_LIBRARIES',	 	JPATH_ROOT.DS.'libraries' );
define( 'JPATH_PLUGINS',		JPATH_ROOT.DS.'plugins'   );
define( 'JPATH_INSTALLATION',	JPATH_ROOT.DS.'installation' );
define( 'JPATH_THEMES'	   ,	JPATH_BASE.DS.'templates' );

define( 'JPATH_KOOWA',          JPATH_PLUGINS.DS.'system'.DS.'koowa');

define( 'JPATH_KOOWA_APP',       JPATH_ROOT.DS.'koowa_unit_testing');
define( 'KPATH_INCLUDES',        JPATH_KOOWA_APP.DS.'includes');
define( 'KPATH_TESTS',           JPATH_KOOWA_APP.DS.'tests');
define( 'KPATH_HELP',            JPATH_KOOWA_APP.DS.'help');

