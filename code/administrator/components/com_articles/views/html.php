<?php
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