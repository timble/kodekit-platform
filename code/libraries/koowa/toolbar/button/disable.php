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
 * Disable button class for a toolbar
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Toolbar
 * @subpackage	Button
 */
class KToolbarButtonDisable extends KToolbarButtonPost
{
	/**
	 * Constructor
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config)
	{
		$config->icon = 'icon-32-unpublish';
		parent::__construct($config);
	}

	public function getOnClick()
	{
		$option	= KRequest::get('get.option', 'cmd');
		$view	= KRequest::get('get.view', 'cmd');
		$token	= JUtility::getToken();
		$json 	= "{method:'post', url:'index.php?option=$option&view=$view&'+id, params:{action:'edit', enabled:0, _token:'$token'}}";

		$msg 	= JText::_('Please select an item from the list');
		return 'var id = KGrid.getIdQuery();'
			.'if(id){new KForm('.$json.').submit();} '
			.'else { alert(\''.$msg.'\'); return false; }';
	}
}