<?php
/**
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
class ComUsersDispatcherDefault extends ComDefaultDispatcherDefault
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        //@TODO Remove when PHP 5.5 becomes a requirement.
        $this->getService('loader')->loadFile(JPATH_ROOT.'/administrator/component/users/legacy/password.php');
    }
    
    protected function _actionDispatch(KCommandContext $context)
	{
        if($context->user->isAuthentic() && $context->request->isGet())
        {  
            //Redirect if user is already logged in
            if($context->request->query->get('view', 'alpha') == 'session')
            {
                $context->response->setRedirect($context->response->getReferrer(), 'You are already logged in!');
                return false;
            }
        }
       
        return parent::_actionDispatch($context);
	}
}