<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Login Controller Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersControllerLogin extends ComDefaultControllerView
{
    protected function _actionDisplay(KCommandContext $context)
    {
        if(!$this->_request->layout) {
            KRequest::set('get.tmpl', 'login');
        }

        return parent::_actionDisplay($context);
    }

    protected function _actionLogin(KCommandContext $context)
    {
        $credentials['username'] = KRequest::get('post.username', 'string');
        $credentials['password'] = KRequest::get('post.password', 'raw');

        $result = KFactory::get('lib.joomla.application')->login($credentials);

        if(!JError::isError($result)) {
            $this->_redirect = 'index.php';
        } else {
            $this->setRedirect('index.php?option=com_users&view=login', $result->getError(), 'error');
        }
    }
}