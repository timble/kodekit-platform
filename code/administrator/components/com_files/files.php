<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Component Loader
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

//echo KService::get('com://admin/files.dispatcher')->dispatch();
try {
$result = KService::get('com://admin/files.controller.folder')
	->container('files-files')
	->folder('test4')
	->name('7')
	->copy(array('destination_name' => '6', 'overwrite' => 1))
	->toArray();
	
var_dump($result);
} 
catch (KControllerException $e) {
	var_dump($e);
}