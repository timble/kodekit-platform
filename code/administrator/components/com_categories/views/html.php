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
            case 'com_contact_details':

                $title = 'Contacts';
                JSubMenuHelper::addEntry(JText::_('Contacts'), 'index.php?option=com_contact');
                JSubMenuHelper::addEntry(JText::_('Categories'), 'index.php?option=com_categories&section=com_contact_details',true);
                break;
        }

        return parent::display();
    }
}