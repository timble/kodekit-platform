<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Http Dispatcher
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Users
 */
class UsersDispatcherHttp extends Library\DispatcherHttp
{
    protected function _actionDispatch(Library\CommandContext $context)
	{        	
        if($context->user->isAuthentic())
        {  
            //Redirect if user is already logged in
            if($context->request->query->get('view', 'alpha') == 'session')
            {
                $menu = $this->getObject('application.pages')->getHome();
                //@TODO : Fix the redirect
                //$this->getObject('application')->redirect('?Itemid='.$menu->id, 'You are already logged in!');
            }
        }

        if(!$context->user->isAuthentic())
        {  
            //Redirect if user is already logged in
            if($context->request->query->get('view', 'alpha') == 'session')
            {
                $menu = $this->getObject('application.pages')->getHome();
                //@TODO : Fix the redirect
                //$this->getObject('application')->redirect('?Itemid='.$menu->id, 'You are already logged out!');
            }
        } 
               
        return parent::_actionDispatch($context);
	}
}