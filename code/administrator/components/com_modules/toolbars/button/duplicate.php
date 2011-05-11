<?php
/** $Id$ */

class ComModulesToolbarButtonDuplicate extends KToolbarButtonPost
{
	//@TODO suggest patch, duplicate is a common action (although incorrectly named "copy" in Joomla)
	public function getOnClick()
	{
		$option	= KRequest::get('get.option', 'cmd');
		$view	= KRequest::get('get.view', 'cmd');
		$token	= JUtility::getToken();
		$json 	= "{method:'post', url:'index.php?option=$option&view=$view&'+id, params:{action:'duplicate', _token:'$token'}}";

		$msg 	= JText::_('Please select an item from the list');
		return 'var id = Koowa.Grid.getIdQuery();'
			.'if(id){new Koowa.Form('.$json.').submit();} '
			.'else { alert(\''.$msg.'\'); return false; }';
	}
}