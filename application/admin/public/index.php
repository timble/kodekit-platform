<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

define('JPATH_ROOT' , realpath($_SERVER['DOCUMENT_ROOT']));
define('JPATH_SITES', JPATH_ROOT.'/sites');

define( 'DS', DIRECTORY_SEPARATOR );

require_once(__DIR__.'/bootstrap.php' );

Nooku\Library\ObjectManager::getInstance()->getObject('application')->run();
