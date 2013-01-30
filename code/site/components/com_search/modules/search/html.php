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
		$this->form_class  = $this->module->params->get('form_class');
		$this->input_class = $this->module->params->get('input_class');
		$this->placeholder = $this->module->params->get('placeholder', JText::_('search'));

		return parent::display();
	}
}