<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Resettable Controller Password Behavior class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersControllerBehaviorPasswordResettable extends KControllerBehaviorAbstract
{
    public function __construct(KConfig $config) {
        parent::__construct($config);

        // TODO Remove when PHP 5.5 becomes a requirement.
        KLoader::loadFile(JPATH_ROOT . '/administrator/components/com_users/legacy.php');
    }

    protected function _beforeControllerRead(KCommandContext $context) {
        $request = $this->getRequest();

        if ($token = $request->token) {
            if ($this->_tokenValid($token)) {
                // Push the token to the view.
                $this->getView()->token = $token;
            } else {
                $this->setRedirect($this->getService('application.pages')->getHome->url, JText::_('INVALID_TOKEN'),
                    'error');
                return false;
            }
        }
    }

    protected function _beforeControllerReset(KCommandContext $context) {
        $password = $this->getModel()->getItem();

        if ($this->_tokenValid($context->data->token)) {
            $context->password = $password;
            $result            = true;
        } else {
            $this->setRedirect($this->getService('application.pages')->getHome()->url,
                JText::_('INVALID_TOKEN'), 'error');
            $result = false;
        }

        return $result;
    }

    protected function _actionReset(KCommandContext $context) {
        $password = $context->password;

        $password->password = $context->data->password;
        $password->reset    = '';
        $password->save();

        if ($password->getStatus() == KDatabase::STATUS_FAILED) {
            $this->setRedirect(KRequest::referrer(), $password->getStatusMessage(), 'error');
            $result = false;
        } else {
            $this->setRedirect($this->getService('application.pages')->getHome()->url,
                JText::_('PASSWORD_RESET_SUCCESS'));
            $result = true;
        }
        return $result;
    }

    protected function _tokenValid($token) {
        $result   = false;
        $password = $this->getModel()->getItem();

        if ($password->reset && (password_verify($token, $password->reset))) {
            $result = true;
        }
        return $result;
    }

    protected function _beforeControllerToken(KCommandContext $context) {

        $data = $context->data;

        $user = $this->getService('com://site/users.model.users')
            ->set('email', $data->email)
            ->getItem();

        if ($user->isNew() || !$user->enabled) {
            $this->setRedirect(KRequest::referrer(), JText::_('COULD_NOT_FIND_USER'), 'error');
            $result = false;
        } else {
            $context->user = $user;
            $result        = true;
        }


        return $result;
    }

    protected function _actionToken(KCommandContext $context) {

        $user = $context->user;

        $password = $user->getPassword();

        $token = $password->getRandom(32);

        $password->reset = $password->getHash($token);
        $password->save();

        $config     = JFactory::getConfig();
        $site_name  = $config->getValue('sitename');
        $from_email = $config->getValue('mailfrom');
        $from_name  = $config->getValue('fromname');
        $url        = $this->getService('koowa:http.url',
            array('url' => "index.php?option=com_users&view=password&layout=form&id={$password->id}&token={$token}"));
        $this->getService('application')->getRouter()->build($url);
        $url     = $url = KRequest::url()->getUrl(KHttpUrl::SCHEME | KHttpUrl::HOST | KHttpUrl::PORT) . $url;
        $subject = JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TITLE', $site_name);
        $body    = JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TEXT', $site_name, $url);

        if (!JUtility::sendMail($from_email, $from_name, $user->email, $subject, $body)) {
            $this->setRedirect(KRequest::referrer(), JText::_('ERROR_SENDING_CONFIRMATION_EMAIL'), 'error');
            $result = false;
        } else {
            $this->setRedirect($this->getService('application.pages')->getHome()->url,
                JText::_('CONFIRMATION_EMAIL_SENT'));
            $result = true;
        }
        return $result;
    }
}