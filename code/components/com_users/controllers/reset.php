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
 * Reset Controller Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersControllerReset extends ComDefaultControllerResource
{
    protected function _actionRequest(KCommandContext $context)
    {
        if(!($email = KRequest::get('post.email', 'email')))
        {
            $this->setRedirect(KRequest::referrer(), JText::_('INVALID_EMAIL_ADDRESS'), 'error');
            return false;
        }

        $user = KFactory::tmp('site::com.users.model.users')
            ->set('email', $email)
            ->getItem();

        if(!$user->id || $user->block)
        {
            $this->setRedirect(KRequest::referrer(), JText::_('COULD_NOT_FIND_USER'), 'error');
            return false;
        }

        $helper = KFactory::tmp('site::com.users.helper.password');
        $token  = $helper->getHash($helper->getRandom());
        $salt   = $helper->getSalt($token);

        $user->activation = md5($token.$salt).':'.$salt;
        $user->save();

        $configuration = KFactory::get('lib.joomla.config');  
        $site_name     = $configuration->getValue('sitename');
        $site_url      = KRequest::url()->get(KHttpUrl::SCHEME | KHttpUrl::HOST | KHttpUrl::PORT);
        $url           = $site_url.JRoute::_('index.php?option=com_users&view=reset&layout=confirm');
        $from_email    = $configuration->getValue('mailfrom');
        $from_name     = $configuration->getValue('fromname');
        $subject       = JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TITLE', $site_name);
        $body          = JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TEXT', $site_name, $token, $url);

        if(!JUtility::sendMail($from_email, $from_name, $email, $subject, $body))
        {
            $this->setRedirect(KRequest::referrer(), JText::_('ERROR_SENDING_CONFIRMATION_EMAIL'), 'error');
            return false;
        }
        else $this->_redirect = 'index.php?option=com_users&view=reset&layout=confirm';
    }

    protected function _actionConfirm(KCommandContext $context)
    {
        $token    = KRequest::get('post.token', 'alnum');
        $username = KRequest::get('post.username', 'string');

        if(strlen($token) != 32)
        {
            $this->setRedirect(KRequest::referrer(), JText::_('INVALID_TOKEN'), 'error');
            return false;
        }

        $user = KFactory::tmp('site::com.users.model.users')
            ->set('username', $username)
            ->getItem();

        if(!$user->id || $user->block)
        {
            $this->setRedirect(KRequest::referrer(), JText::_('INVALID_TOKEN'), 'error');
            return false;
        }

        $parts = explode(':', $user->activation);

        if(!isset($parts[1]))
        {
            $this->setRedirect(KReuqest::referrer(), JText::_('INVALID_TOKEN'), 'error');
            return false;
        }

        $helper = KFactory::tmp('site::com.users.helper.password');

        if($parts[0] != $helper->getCrypted($token, $parts[1]))
        {
            $this->setRedirect(KRequest::referrer(), JText::_('INVALID_TOKEN'), 'error');
            return false;
        }

        KRequest::set('session.com.users.id', $user->id);
        KRequest::set('session.com.users.token', $token);

        $this->_redirect = 'index.php?option=com_users&view=reset&layout=complete';
    }

    protected function _actionComplete(KCommandContext $context)
    {
        $password        = KRequest::get('post.password', 'raw');
        $password_verify = KRequest::get('post.password_verify', 'raw');

        if(!$password)
        {
            $this->setRedirect(KRequest::referrer(), JText::_('MUST_SUPPLY_PASSWORD'), 'error');
            return false;
        }

        if($password != $password_verify)
        {
            $this->setRedirect(KRequest::referrer(), JText::_('PASSWORDS_DO_NOT_MATCH_LOW'), 'error');
            return false;
        }

        $helper = KFactory::tmp('site::com.users.helper.password');

        $salt     = $helper->getRandom(32);
        $password = $helper->getCrypted($password, $salt).':'.$salt;

        $user     = KFactory::get('lib.joomla.user', KRequest::get('session.com.users.id', 'int'));

        JPluginHelper::importPlugin('user');
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onBeforeStoreUser', array($user->getProperties(), false));

        KFactory::tmp('site::com.users.model.users')
            ->set('id', $user->id)
            ->getItem()
            ->set('password', $password)
            ->set('activation', '')
            ->save();

        $user->password         = $password;
        $user->activation       = '';
        $user->password_clear   = $password_verify;

        $dispatcher->trigger('onAfterStoreUser', array($user->getProperties(), false, $result));

        KRequest::set('session.com.users.id', null);
        KRequest::set('session.com.users.token', null);

        $this->setRedirect('index.php?option=com_users&view=login', JText::_('PASSWORD_RESET_SUCCESS'));
    }
}