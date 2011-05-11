<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Next Toolbar Button Class
 * 
 * @author    	Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 */

class ComModulesToolbarButtonNext extends KToolbarButtonGet
{
	public function getOnClick()
	{
		$id		= 'modules-module-list';
		$notice = JText::_('Select a module before clicking next.');
		$event	= "$('$id').getElements('[name=module]:checked').length ? $('$id').submit() : alert(".json_encode($notice).");";

		return htmlspecialchars($event);
	}
}