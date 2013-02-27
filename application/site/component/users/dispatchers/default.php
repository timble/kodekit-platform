<?php
/**
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
class ComUsersDispatcherDefault extends ComDefaultDispatcherDefault
{
    protected function _initialize(KConfig $config)
    {
        parent::_initialize($config);

        //Force the view to prevent a redirect
        if($this->getUser()->isAuthentic() && $this->getRequest()->isGet())
        {  
            $view = $this->getRequest()->get('view', 'alpha');
            
		    if(!in_array($view, array('session', 'remind', 'reset', 'user'))) {
                $this->getRequest()->query->set('view', 'session');
            }
        }
    }
	
    protected function _actionDispatch(KCommandContext $context)
	{        	
        if($context->user->isAuthentic())
        {  
            //Redirect if user is already logged in
            if($context->request->query->get('view', 'alpha') == 'session')
            {
                $menu = $this->getService('application.pages')->getHome();
                //@TODO : Fix the redirect
                //$this->getService('application')->redirect('?Itemid='.$menu->id, 'You are already logged in!');
            }
        }

        if(!$context->user->isAuthentic())
        {  
            //Redirect if user is already logged in
            if($context->request->query->get('view', 'alpha') == 'session')
            {
                $menu = $this->getService('application.pages')->getHome();
                //@TODO : Fix the redirect
                //$this->getService('application')->redirect('?Itemid='.$menu->id, 'You are already logged out!');
            }
        } 
               
        return parent::_actionDispatch($context);
	}
}