<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Category HTML View Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories  
 */
class ComCategoriesViewHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $section=KRequest::get('get.section','string');
        
        if ( !$section ) {
            $section = $this->getModel()->getItem()->section;
        }
        $section = (is_numeric( $section ) || empty( $section)) ? 'com_content' : $section;

        switch($section)
        {
            case 'com_content':
                
                $title = 'Articles'; 
                JSubMenuHelper::addEntry(JText::_('Articles'), 'index.php?option=com_content');
                JSubMenuHelper::addEntry(JText::_('Sections'), 'index.php?option=com_sections&scope=content');
                JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_categories&section=com_content',true);
                JSubMenuHelper::addEntry(JText::_('Front Page'), 'index.php?option=com_frontpage');
		
                if(JFactory::getUser()->authorize('com_trash', 'manage')) {
                    JSubMenuHelper::addEntry(JText::_('Trash'), 'index.php?option=com_trash&task=viewContent');
                }
                break;
                
            case 'com_banner':
                
                $title = 'Banners';
                JSubMenuHelper::addEntry(JText::_('Banners'), 'index.php?option=com_banners');
                JSubMenuHelper::addEntry(JText::_('Clients'), 'index.php?option=com_banners&c=client');
                JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_categories&section='.$section,true);
                break;
                
            case 'com_contact_details':
                
                $title = 'Contacts';
                JSubMenuHelper::addEntry(JText::_('Contacts'), 'index.php?option=com_contact');
                JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_categories&section=com_contact_details',true);
                break;
                
            default:
                
                $title= substr($section,4);
                JSubMenuHelper::addEntry(JText::_($title), 'index.php?option='.$section);
                JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_categories&section='.$section,true);
        }
        
	    $this->getToolbar()->setTitle(JText::_(ucfirst($title).' Category Manager'));	
        
        return parent::display();
    }	
}