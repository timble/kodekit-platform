<?php
/**
 * @version     $Id: html.php 1216 2011-05-12 16:59:54Z stiandidriksen $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Modules HTML View Class
 *   
 * @author    	Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 */
class ComModulesViewHtml extends ComDefaultViewHtml
{
	public function display()
	{
		JSubMenuHelper::addEntry(JText::_('Modules'), 'index.php?option=com_modules&view=modules', true);
		JSubMenuHelper::addEntry(JText::_('Plugins'), 'index.php?option=com_plugins&view=plugins');
		JSubMenuHelper::addEntry(JText::_('Templates'), 'index.php?option=com_templates&view=templates');
		JSubMenuHelper::addEntry(JText::_('Languages'), 'index.php?option=com_languages&view=languages');
		
		return parent::display();
	}
}