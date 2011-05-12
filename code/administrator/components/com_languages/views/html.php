<?php
/**
 * @version     $Id: templates.php 1161 2011-05-11 14:52:09Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Html View Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages
 */

class ComLanguagesViewHtml extends ComDefaultViewHtml
{
	public function display()
	{
        JSubMenuHelper::addEntry(JText::_('Modules'), 'index.php?option=com_modules&view=modules');
        JSubMenuHelper::addEntry(JText::_('Plugins'), 'index.php?option=com_plugins&view=plugins');
        JSubMenuHelper::addEntry(JText::_('Templates'), 'index.php?option=com_templates&view=templates');
        JSubMenuHelper::addEntry(JText::_('Languages'), 'index.php?option=com_languages&view=languages', true);

		return parent::display();
	}
}