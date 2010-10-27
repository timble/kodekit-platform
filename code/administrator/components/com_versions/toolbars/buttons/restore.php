<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Versions
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Restore Toolbar Button
.*
 * @author      Johan Janssens <johan@timble.net>
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Versions
 */
class ComVersionsToolbarButtonRestore extends KToolbarButtonPost
{
	public function getOnClick()
	{
		$option	= KRequest::get('get.option', 'cmd');
		$view	= KRequest::get('get.view', 'cmd');
		$token	= JUtility::getToken();
		$json 	= "{method:'post', url:'index.php?option=$option&view=$view&trashed=1&'+id, params:{action:'restore', _token:'$token'}}";

		$msg 	= JText::_('Please select an item from the list');
		return 'var id = KGrid.getIdQuery();'
			.'if(id){new KForm('.$json.').submit();} '
			.'else { alert(\''.$msg.'\'); return false; }';
	}
}