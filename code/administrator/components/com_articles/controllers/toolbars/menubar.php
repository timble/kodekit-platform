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
 * Articles Menubar Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */
class ComArticlesControllerToolbarMenubar extends ComDefaultControllerToolbarMenubar
{
    public function getCommands()
    { 
        $name = $this->getController()->getIdentifier()->name;
        
        $this->addCommand('Articles', array(
        	'href'   => JRoute::_('index.php?option=com_articles&view=articles'),
        	'active' => ($name == 'article')
        ));
        
        $this->addCommand('Sections' , array(
        	'href' => JRoute::_('index.php?option=com_sections&scope=content'), 
        ));
        
        $this->addCommand('Categories', array(
        	'href' => JRoute::_('index.php?option=com_articles&view=categories'),
            'active' => ($name == 'category')
        ));
         
        return parent::getCommands();
    }
}