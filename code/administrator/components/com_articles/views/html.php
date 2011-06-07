<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Articles Html View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComArticlesViewHtml extends ComDefaultViewHtml
{
    public function display()
    {
        JSubMenuHelper::addEntry(JText::_('Articles'), 'index.php?option=com_articles&view=articles', true);
        JSubMenuHelper::addEntry(JText::_('Sections'), 'index.php?option=com_sections&scope=content');
        JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_categories&section=com_content');
        JSubMenuHelper::addEntry(JText::_('Front Page'), 'index.php?option=com_frontpage');

        if(JFactory::getUser()->authorize('com_trash', 'manage')) {
            JSubMenuHelper::addEntry(JText::_('Trash'), 'index.php?option=com_trash&task=viewContent');
        }

        return parent::display();
    }
}