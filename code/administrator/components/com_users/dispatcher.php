<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Users Dispatcher Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category	Nooku
 * @package		Nooku_Server
 * @subpackage	Users
 */
class ComUsersDispatcher extends ComDefaultDispatcher
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);
        
        require_once __DIR__.'/legacy.php';
    }
    
    protected function _initialize(KConfig $config)
    {  
        //Force the view to prevent a redirect
        if(JFactory::getUser()->guest && KRequest::method() == KHttpRequest::GET) {  
            $config->request = array('view' => 'session');
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
                //@TODO : Set message in session
                //$this->getService('application')->redirect('index.php', 'You are already logged in!');
                //$context->response->setRedirect('index.php');
                return false;
            }
        }
       
        return parent::_actionDispatch($context);
	}
}