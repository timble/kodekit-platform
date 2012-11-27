<?php
/**
 * @version		$Id: dispatcher.php 3024 2011-10-09 01:44:30Z johanjanssens $
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Users Dispatcher Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */
class ComUsersDispatcher extends ComDefaultDispatcher
{
    protected function _initialize(KConfig $config)
    {  
        //Force the view to prevent a redirect
        if(JFactory::getUser()->guest && KRequest::method() == KHttpRequest::GET) 
        {  
            $view = KRequest::get('get.view', 'cmd');
            
		    if(!in_array($view, array('session', 'remind', 'reset', 'user'))) {
                $config->request = array('view' => 'session');
            }
        } 
        
        parent::_initialize($config);
    }
	
    protected function _actionDispatch(KCommandContext $context)
	{        	
        if(!JFactory::getUser()->guest) 
        {  
            //Redirect if user is already logged in
            if($this->getRequest()->view == 'session')
            {
                $menu = $this->getService('application.pages')->getHome();
                //@TODO : Fix the redirect
                //$this->getService('application')->redirect('index.php?Itemid='.$menu->id, 'You are already logged in!');
            }
        }

        if(JFactory::getUser()->guest) 
        {  
            //Redirect if user is already logged in
            if($this->getRequest()->view == 'session')
            {
                $menu = $this->getService('application.pages')->getHome();
                //@TODO : Fix the redirect
                //$this->getService('application')->redirect('index.php?Itemid='.$menu->id, 'You are already logged out!');
            }
        } 
               
        return parent::_actionDispatch($context);
	}
}