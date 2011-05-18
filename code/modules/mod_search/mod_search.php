<?php
/**
 * @version		$Id: weblinks.php 1291 2011-05-16 22:13:45Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Search Module
 *
 * @author    	Arunas Mazeika <http://nooku.assembla.com/profile/amazeika>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 */

KFactory::tmp('site::mod.search.view', array(
	'params' => $params, 
	'module' => $module, 
	'attribs' => $attribs)
)->display();
