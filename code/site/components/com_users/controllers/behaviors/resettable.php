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
 * Resettable Controller Behavior class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Users
 */
class ComUsersControllerBehaviorResettable extends KControllerBehaviorAbstract
{
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        //@TODO Remove when PHP 5.5 becomes a requirement.
        KLoader::loadFile(JPATH_ROOT . '/administrator/components/com_users/legacy.php');
    }

    protected function _beforeControllerRead(KCommandContext $context)
    {
        $request = $this->getRequest();

        if ($reset = $request->reset) {
            if ($this->_tokenValid($reset)) {
                // Push the token to the view.
                $this->getView()->reset = $reset;
            } else {
                $context->response->setRedirect($this->getService('application.pages')->getHome->url);
                //@TODO : Set message in session
                //$this->setRedirect($this->getService('application.pages')->getHome->url, JText::_('INVALID_TOKEN'),'error');
                return false;
            }
        }
    }

    protected function _beforeControllerReset(KCommandContext $context)
    {
        $result = true;

        if (!$this->_tokenValid($context->data->reset))
        {
            $context->response->setRedirect($this->getService('application.pages')->getHome->url);
            //@TODO : Set message in session
            //$this->setRedirect($this->getService('application.pages')->getHome->url, JText::_('INVALID_TOKEN'),'error');

            $result = false;
        }

        return $result;
    }

    protected function _actionReset(KCommandContext $context)
    {
        $password = $this->getModel()->getItem()->getPassword();

        $password->password = $context->data->password;
        $password->reset    = '';
        $password->save();

        if ($password->getStatus() == KDatabase::STATUS_FAILED)
        {
            $context->response->setRedirect(KRequest::referrer());
            //@TODO : Set message in session
            //$this->setRedirect(KRequest::referrer(), $password->getStatusMessage(), 'error');
            $result = false;
        } else {
            $context->response->setRedirect($this->getService('application.pages')->getHome()->url);
            //@TODO : Set message in session
            //$this->setRedirect($this->getService('application.pages')->getHome()->url,JText::_('PASSWORD_RESET_SUCCESS'));
            $result = true;
        }
        return $result;
    }

    protected function _tokenValid($token)
    {
        $result   = false;
        $password = $this->getModel()->getItem()->getPassword();

        if ($password->reset && ($password->verify($token, $password->reset))) {
            $result = true;
        }

        return $result;
    }

    protected function _beforeControllerToken(KCommandContext $context)
    {
        $data = $context->data;

        $user = $this->getService('com://site/users.model.users')
            ->set('email', $data->email)
            ->getItem();

        if ($user->isNew() || !$user->enabled)
        {
            $context->response->setRedirect(KRequest::referrer());
            //@TODO : Set message in session
            //$this->setRedirect(KRequest::referrer(), JText::_('COULD_NOT_FIND_USER'), 'error');
            $result = false;
        } else {
            $context->user = $user;
            $result        = true;
        }


        return $result;
    }

    protected function _actionToken(KCommandContext $context)
    {
        $user     = $context->user;
        $password = $user->getPassword();
        $reset    = $password->setReset();

        $application     = $this->getService('application');
        $site_name  = $application->getCfg('sitename');

        $url        = $this->getService('koowa:http.url',
            array('url' => "index.php?option=com_users&view=user&layout=password&uuid={$user->uuid}&reset={$reset}"));
        $this->getService('application')->getRouter()->build($url);
        $url     = $url = KRequest::url()->getUrl(KHttpUrl::SCHEME | KHttpUrl::HOST | KHttpUrl::PORT) . $url;
        $subject = JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TITLE', $site_name);
        $message    = JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TEXT', $site_name, $url);

        if (!$user->notify(array('subject' => $subject, 'message' => $message)))
        {
            $context->response->setRedirect(KRequest::referrer());
            //@TODO : Set message in session
            //$this->setRedirect(KRequest::referrer(), JText::_('ERROR_SENDING_CONFIRMATION_EMAIL'), 'error');
            $result = false;
        }
        else
        {
            $context->response->setRedirect($this->getService('application.pages')->getHome()->link_url);
            //@TODO : Set message in session
            //$this->setRedirect($this->getService('application.pages')->getHome()->url, JText::_('CONFIRMATION_EMAIL_SENT'));
            $result = true;
        }

        return $result;
    }
}