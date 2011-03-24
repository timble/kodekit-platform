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
 * Save Toolbar Button Class
 * 
 * @author		John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 */
class ComCategoriesToolbarButtonSave extends KToolbarButtonSave
{
    public function getOnClick()
    {
        $msg    = JText::_('Category must have a title');
        return 'var form =document.adminForm;'
            .'if(form.title.value != \'\'){'.parent::getOnClick().'} '
            .'else { alert(\''.$msg.'\'); return false; }';
    }

}