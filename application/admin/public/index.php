<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

define('JPATH_ROOT'         , realpath($_SERVER['DOCUMENT_ROOT']));
define('JPATH_APPLICATION'  , JPATH_ROOT.'/application/admin');
define('JPATH_VENDOR'       , JPATH_ROOT.'/vendor' );
define('JPATH_SITES'        , JPATH_ROOT.'/sites');

define('JPATH_BASE'         , JPATH_APPLICATION );

define( 'DS', DIRECTORY_SEPARATOR );

require_once(__DIR__.'/bootstrap.php' );

Nooku\Library\ObjectManager::getInstance()->getObject('application')->run();
