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
        if(JFactory::getUser()->guest) 
        {  
            if(KRequest::method() == KHttpRequest::GET) {
                $config->request = array('view' => 'login');
            }
        } 
        
        return parent::_initialize($config);
    }
    
    protected function _actionDispatch(KCommandContext $context)
	{        	
        if(!JFactory::getUser()->guest) 
        {  
            //Redirect if user is already logged in
            if($this->getRequest()->view == 'login') {
                JFactory::getApplication()->redirect('index.php');
            }
        } 
               
        return parent::_actionDispatch($context);
	}
}