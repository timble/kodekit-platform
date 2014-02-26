<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Users;

/**
 * Resettable Controller Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Users
 */
class UsersControllerBehaviorResettable extends Users\ControllerBehaviorResettable
{
    protected function _beforeAdd(Library\ControllerContextInterface $context)
    {
        // Force a password reset.
        if (!$context->request->data->get('password', 'string')) {
            $context->request->data->password_reset = true;
        }
    }

    protected function _afterAdd(Library\ControllerContextInterface $context)
    {
        $user = $context->result;
        if ($context->request->data->get('password_reset', 'boolean') && $user->getStatus() !== Library\Database::STATUS_FAILED)
        {
            if (!$this->token($context)) {
                $context->response->addMessage('Failed to deliver the password reset token', 'error');
            }
        }
    }

    protected function _afterEdit(Library\ControllerContextInterface $context)
    {
        return $this->_afterAdd($context);
    }

    protected function _actionToken(Library\ControllerContextInterface $context)
    {
        $result = true;

        $row   = $context->row;
        $token = $row->getPassword()->setReset();

        $extension = $this->getObject('application.extensions')->getExtension('users');
        $page      = $this->getObject('application.pages')->find(array(
            'extensions_extension_id' => $extension->id,
            'access'                  => 0,
            'link'                    => array(array('view' => 'user'))));

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

        if (!$row->notify(array('subject' => $subject, 'message' => $message)))
        {
            $result = false;
        }

        return $result;
    }
}