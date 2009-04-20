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
 * Edit button class for a toolbar
 * 
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @category	Koowa
 * @package		Koowa_Toolbar
 * @subpackage	Button
 */
class KToolbarButtonEdit extends KToolbarButtonAbstract
{
	public function getOnClick()
	{
		$msg = JText::_('Please select an item from the list');
		return 'var id = Koowa.Grid.getFirstSelected();'
			.'if(id){this.href+=id;} '
			.'else { alert(\''.$msg.'\'); return false; }';
	}
	
	public function getLink()
	{
		$option = KInput::get('option', 'get', 'cmd');
		$view	= KInflector::singularize(KInput::get('view', 'get', 'cmd'));
		return 'index.php?option='.$option.'&view='.$view.'&layout=form&id=';
	}
}