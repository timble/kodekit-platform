<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Component Dispatcher
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */
class ComUsersDispatcher extends ComDefaultDispatcher
{
    protected function _initialize(KConfig $config)
    {  
        if(KFactory::get('lib.joomla.user')->guest) 
        {  
            if(KRequest::method() == KHttpRequest::GET) 
            {
                //Force the view to prevent a redirect
                KRequest::set('get.view', 'login');
                
                $config->controller = 'login';
            }
        } 
        
        parent::_initialize($config);
    }
    
    protected function _actionDispatch(KCommandContext $context)
	{        	
        if(!KFactory::get('lib.joomla.user')->guest) 
        {  
            //Redirect if user is already logged in
            if($this->getRequest()->view == 'login') {
                KFactory::get('lib.joomla.application')->redirect('index.php');
            }
        } 
               
        return parent::_actionDispatch($context);
	}
}