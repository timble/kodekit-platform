<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library, Nooku\Component\Users;

/**
 * Resettable Controller Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Users
 */
class UsersControllerBehaviorResettable extends Users\ControllerBehaviorResettable
{
    protected function _beforeRead(Library\ControllerContextInterface $context)
    {
        $result = true;

        // Push the token to the view.
        if ($token = $context->request->query->get('token', $this->_filter))
        {
            $user = $this->getModel()->getRow();

            // Only passwords from enabled users can be reset.
            if (!$user->enabled)
            {
                $url = $this->getObject('application.pages')->getHome()->getLink();
                $this->getObject('application')->getRouter()->build($url);
                $context->response->setRedirect($url, JText::_('CANNOT_RESET_USER_NOT_ENABLED'),
                    Library\ControllerResponseInterface::FLASH_ERROR);
                $result = false;
            }
            else
            {
                $this->getView()->token = $token;
            }
        }

        return $result;
    }

    protected function _beforeReset(Library\ControllerContextInterface $context)
    {
        $result = true;

        if (!parent::_beforeReset($context))
        {
            $url = $this->getObject('application.pages')->getHome()->getLink();
            $this->getObject('application')->getRouter()->build($url);

            $context->response->setRedirect($url, \JText::_('INVALID_REQUEST'), 'error');
            $result = false;
        }

        return $result;
    }

    protected function _beforeToken(Library\ControllerContextInterface $context)
    {
        $result = false;

        if (parent::_beforeToken($context))
        {
            $page = $this->getObject('application.pages')->find(array(
                'component' => 'users',
                'access'    => 0,
                'link'      => array(array('view' => 'user'))));

            if ($page)
            {
                $context->page = $page;
                $result = true;
            }
        }

        return $result;
    }

    protected function _afterToken(Library\ControllerContextInterface $context)
    {
        if ($context->result)
        {
            $page  = $context->page;
            $token = $context->token;
            $row   = $context->row;

            $url                  = $page->getLink();
            $url->query['layout'] = 'password';
            $url->query['token']  = $token;
            $url->query['uuid']   = $row->uuid;

            $this->getObject('application')->getRouter()->build($url);

            $url = $context->request->getUrl()
                                    ->toString(Library\HttpUrl::SCHEME | Library\HttpUrl::HOST | Library\HttpUrl::PORT) . $url;

            $site_name = \JFactory::getConfig()->getValue('sitename');

            $subject = \JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TITLE', $site_name);
            // TODO Fix when language package is re-factored.
            //$message    = \JText::sprintf('PASSWORD_RESET_CONFIRMATION_EMAIL_TEXT', $site_name, $url);
            $message = $url;

            if ($row->notify(array('subject' => $subject, 'message' => $message)))
            {
                $message = JText::_('CONFIRMATION_EMAIL_SUCCESS');
                $type    = 'success';

                $url = $this->getObject('application.pages')->getHome()->getLink();
                $this->getObject('application')->getRouter()->build($url);
            }
            else
            {
                $message = JText::_('ERROR_SENDING_CONFIRMATION_EMAIL');
                $type    = 'error';

                $url = $context->request->getReferrer();
            }

            $context->response->setRedirect($url, $message, $type);
        }
    }

    protected function _afterReset(Library\ControllerContextInterface $context)
    {
        if ($context->result)
        {
            $message = JText::_('PASSWORD_RESET_SUCCESS');

            $url     = $this->getObject('application.pages')->getHome()->getLink();
            $this->getObject('application')->getRouter()->build($url);

        }
        else
        {
            $message = $context->error;
            $type   = 'error';

            $url    = $context->request->getReferrer();
        }

        $context->response->setRedirect($url, $message, $type);
    }
}