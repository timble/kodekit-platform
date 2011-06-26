<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Weblinks Menubar Class
 *
 * @author    	Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */
class ComWeblinksControllerToolbarMenubar extends ComDefaultControllerToolbarMenubar
{
    public function getCommands()
    { 
        $name = $this->getController()->getIdentifier()->name;
        
        $this->addCommand('Weblinks', array(
        	'href'   => JRoute::_('index.php?option=com_weblinks&view=weblinks'),
        	'active' => ($name == 'weblink')
        ));
        
        $this->addCommand('Categories', array(
        	'href' => JRoute::_('index.php?option=com_weblinks&view=categories'),
            'active' => ($name == 'category')
        ));
         
        return parent::getCommands();
    }
}