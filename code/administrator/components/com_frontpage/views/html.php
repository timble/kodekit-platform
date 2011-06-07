<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Frontpage
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Frontpage HTML View Class
 *
 * @author      Richie Mortimer <http://nooku.assembla.com/profile/ravenlife>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Frontpage 
 */
class ComFrontpageViewHtml extends ComDefaultViewHtml
{
    public function display()
    {
        JSubMenuHelper::addEntry(JText::_('Articles'), 'index.php?option=com_content');
        JSubMenuHelper::addEntry(JText::_('Sections'), 'index.php?option=com_sections&scope=content');
        JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_categories&section=com_content');
        JSubMenuHelper::addEntry(JText::_('Front Page'), 'index.php?option=com_frontpage', true);
        
        //if the user is authorised to manage trash, add a sub menu item for com_trash
        if( KFactory::get('lib.joomla.user')->authorize('com_trash', 'manage') ) {
            JSubMenuHelper::addEntry(JText::_('Trash'), 'index.php?option=com_trash&task=viewContent');
        }
        
        return parent::display();
    }
}