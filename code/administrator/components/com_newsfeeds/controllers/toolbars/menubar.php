<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Newsfeeds Menubar Class
 *
 * @author    	Babs Gšsgens <http://nooku.assembla.com/profile/babsgosgens>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 */
class ComNewsfeedsControllerToolbarMenubar extends ComDefaultControllerToolbarMenubar
{
    public function getCommands()
    { 
        $name = $this->getController()->getIdentifier()->name;
        
        $this->addCommand('Newsfeeds', array(
        	'href'   => JRoute::_('index.php?option=com_newsfeeds&view=newsfeeds'),
        	'active' => ($name == 'newsfeed')
        ));
        
        $this->addCommand('Categories', array(
        	'href' => JRoute::_('index.php?option=com_newsfeeds&view=categories'),
            'active' => ($name == 'category')
        ));
         
        return parent::getCommands();
    }
}