<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Cache Menubar Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 */
class ComCacheControllerToolbarMenubar extends ComDefaultControllerToolbarMenubar
{
    public function getCommands()
    { 
         $name = $this->getController()->getIdentifier()->name;
        
        $this->addCommand('Groups', array(
        	'href' => JRoute::_('index.php?option=com_cache&view=groups'),
        	'active' => ($name == 'group')
        ));
        
        $this->addCommand('Keys', array(
        	'href' => JRoute::_('index.php?option=com_cache&view=keys'),
            'active' => ($name == 'key')
        ));
         
        return parent::getCommands();
    }
}