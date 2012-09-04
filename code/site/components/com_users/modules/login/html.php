<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Module Login View
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Users
 */
 
class ComUsersModuleLoginHtml extends ComDefaultModuleDefaultHtml
{
    protected function _initialize(KConfig $config)
    { 
        $config->append(array(
            'layout' => JFactory::getUser()->guest ? 'login' : 'logout'
        ));
        
        parent::_initialize($config);
    }
    
    public function display()
    { 
        $this->show_greeting = $this->module->params->get('greeting', 1);
        $this->name          = $this->module->params->get('name');  
        $this->usesecure     = $this->module->params->get('usesecure');   
        $this->pretext       = $this->module->params->get('pretext');
        $this->posttext      = $this->module->params->get('posttext');
        $this->allow_registration = $this->getService('application.components')->users->params->get('allowUserRegistration');
        
        // Assign vars and render view
		$this->assign('user', JFactory::getUser());    
          
        return parent::display();
    }
} 