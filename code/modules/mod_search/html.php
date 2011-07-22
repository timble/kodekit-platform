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
 * Search Module Html Class
 *
 * @author    	Arunas Mazeika <http://nooku.assembla.com/profile/amazeika>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 */
class ModSearchHtml extends ModDefaultHtml
{
	/**
	 * Renders the views output
	 *
	 * @return string
	 */
	public function display()
	{
		$this->assign('button'         , $this->module->params->get('button', ''));
		$this->assign('button_pos'     , $this->module->params->get('button_pos', 'left'));
		$this->assign('button_text'    , $this->module->params->get('button_text', JText::_('Search')));
		$this->assign('width'          , intval($this->module->params->get('width', 20)));
		$this->assign('maxlength'      , $this->module->params->get('width') > 20 ? $this->module->params->get('width') : 20);
		$this->assign('text'           , $this->module->params->get('text', JText::_('search...')));
		$this->assign('moduleclass_sfx', $this->module->params->get('moduleclass_sfx'));
		
		// If no menu item id is given, or its value is zero, attempt to use 
		// the current item id of the current menu.
		$itemid = intval($this->module->params->get('set_itemid', 0));
		$itemid = $itemid > 0 ? $itemid : KRequest::get('get.Itemid', 'int');
		
		$this->assign('itemid', $itemid);
		
		return parent::display();
	}
}