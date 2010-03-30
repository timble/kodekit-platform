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
 * Edit button class for a toolbar
 * 
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Toolbar
 * @subpackage	Button
 */
class KToolbarButtonEdit extends KToolbarButtonAbstract
{
	public function getOnClick()
	{
		$msg = JText::_('Please select an item from the list');
		return 'var id = KGrid.getFirstSelected();'
			.'if(id){this.href+=\'&id=\'+id;} ' 
			.'else { alert(\''.$msg.'\'); return false; }';
	}
	
	public function getLink()
	{
		$option = KRequest::get('get.option', 'cmd');
		$view	= KInflector::singularize(KRequest::get('get.view', 'cmd'));
		return 'index.php?option='.$option.'&view='.$view; 
	}
}