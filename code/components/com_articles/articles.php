<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright	Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @author 		Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
defined('KOOWA') or die('Restricted access');

// Load aliases
KLoader::loadIdentifier('com://site/articles.aliases');

echo KService::get('com://site/articles.dispatcher')->dispatch();