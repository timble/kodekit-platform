<?php
/**
* @version      $Id: default.php 628 2009-02-20 18:18:45Z mathias $
* @category		Koowa
* @package		Koowa_Toolbar
* @subpackage	Button
* @copyright    Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
*/

/**
 * Cancel button class for a toolbar
 * 
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @category	Koowa
 * @package		Koowa_Toolbar
 * @subpackage	Button
 */
class KToolbarButtonCancel extends KToolbarButtonAbstract
{
	public function getLink()
	{
		$option = KRequest::get('get.option', 'cmd');
		$view	= KInflector::pluralize(KRequest::get('get.view', 'cmd'));
		return 'index.php?option='.$option.'&view='.$view;
	}
}