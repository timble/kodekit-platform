<?php
/**
 * @version		$Id: weblinks.php 1291 2011-05-16 22:13:45Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Search
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Search Module Html Class
 *
 * @author    	Arunas Mazeika <http://nooku.assembla.com/profile/amazeika>
 * @package     Nooku_Server
 * @subpackage  Search
 */
class ComSearchModuleSearchHtml extends ComDefaultModuleDefaultHtml
{
	public function display()
	{
		$this->assign('button'         , $this->module->params->get('button', ''));
		$this->assign('button_pos'     , $this->module->params->get('button_pos', 'left'));
		$this->assign('button_text'    , $this->module->params->get('button_text', JText::_('Search')));
		$this->assign('width'          , intval($this->module->params->get('width', 20)));
		$this->assign('maxlength'      , $this->module->params->get('width') > 20 ? $this->module->params->get('width') : 20);
		$this->assign('text'           , $this->module->params->get('text', JText::_('search...')));

		return parent::display();
	}
}