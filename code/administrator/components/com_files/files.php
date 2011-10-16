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

try {
	echo KService::get('com://admin/files.dispatcher')->dispatch();
}
catch (KControllerException $e){
	if (KRequest::get('get.format', 'cmd') == 'json') {
		$obj = new stdClass;
		$obj->status = false;
		$obj->error = $e->getMessage();
		$obj->code = $e->getCode();
		
		$code = KRequest::get('get.plupload', 'int') ? 200 : $e->getCode();
		
		JResponse::setHeader('status', $code.' '.str_replace("\n", ' ', $e->getMessage()));
		
		echo json_encode($obj);
	}
	else {
		throw $e;
	}
}