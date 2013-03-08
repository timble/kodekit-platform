<?php
/**
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Module Login View
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Users
 */
 
class ComUsersModuleLoginHtml extends ComDefaultModuleDefaultHtml
{
    protected function _initialize(Framework\Config $config)
    { 
        $config->append(array(
            'layout' => $this->getService('user')->isAuthentic() ? 'logout' : 'login'
        ));
        
        parent::_initialize($config);
    }
    
    public function render()
    { 
        $this->name          = $this->module->params->get('name');
        $this->usesecure     = $this->module->params->get('usesecure');
        $this->show_title    = $this->module->params->get('show_title', false);
        $this->allow_registration = $this->getService('application.components')->users->params->get('allowUserRegistration');

        return parent::render();
    }
} 