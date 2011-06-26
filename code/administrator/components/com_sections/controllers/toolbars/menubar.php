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
 * Sections Toolbar Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Section
 */
class ComSectionsControllerToolbarMenubar extends ComDefaultControllerToolbarMenubar
{
    public function getCommands()
    { 
        $this->addCommand('Articles', array(
        	'href'   => JRoute::_('index.php?option=com_articles&view=articles'),
        ));
        
        $this->addCommand('Sections' , array(
        	'href'   => JRoute::_('index.php?option=com_sections&scope=content'),
            'active' => true  
        ));
        
        $this->addCommand('Categories', array(
        	'href' => JRoute::_('index.php?option=com_categories&section=com_content'),
        ));
         
        return parent::getCommands();
    }
}