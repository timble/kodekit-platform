<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Section HTML View Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections    
 */
class ComSectionsViewHtml extends ComDefaultViewHtml
{
	public function display()
	{
		JSubMenuHelper::addEntry(JText::_('Articles'), 'index.php?option=com_content');
		JSubMenuHelper::addEntry(JText::_('Sections'), 'index.php?option=com_sections&scope=content', true);
		JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_categories&section=com_content');
		JSubMenuHelper::addEntry(JText::_('Front Page'), 'index.php?option=com_frontpage');
		
		if(JFactory::getUser()->authorize('com_trash', 'manage')) {
			JSubMenuHelper::addEntry(JText::_('Trash'), 'index.php?option=com_trash&task=viewContent');
		}
		
		return parent::display();
	}	
}