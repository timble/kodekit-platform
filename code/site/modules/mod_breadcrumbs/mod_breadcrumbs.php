<?php
/**
 * @version		$Id$
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright	Copyright (C) 2010 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Breadcrumbs Module
 *
 * @author   	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package    	Nooku_Server
 * @subpackage 	Default
 */

echo KService::get('mod://site/breadcrumbs.html')
    ->module($module)
    ->attribs($attribs)
    ->params($params)
    ->display();