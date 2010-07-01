<?php
/**
* @version      $Id$
* @category		Koowa
* @package		Koowa_Toolbar
* @subpackage	Button
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
*/

/**
 * Apply button class for a toolbar
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Toolbar
 * @subpackage	Button
 */
class KToolbarButtonApply extends KToolbarButtonPost
{
	public function getOnClick()
	{
		$option	= KRequest::get('get.option', 'cmd');
		$view	= KRequest::get('get.view', 'cmd');
		$id		= KRequest::get('get.id', 'int');
		$token 	= JUtility::getToken();
		$json 	= "{method:'post', url:'index.php?option=$option&view=$view&id=$id', formelem:'adminForm', params:{action:'apply', _token:'$token'}}";

		return 'new KForm('.$json.').submit();';
	}

}