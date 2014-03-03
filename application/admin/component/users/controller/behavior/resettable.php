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
    protected function _beforeToken(Library\ControllerContextInterface $context)
    {
        $result = false;

        if (parent::_beforeToken($context))
        {
            $page = $this->getObject('application.pages')->find(array(
                'component'               => 'users',
                'access'                  => 0,
                'link'                    => array(array('view' => 'user'))));

            if ($page)
            {
                $context->page = $page;
                $result        = true;
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

            if (!$row->notify(array('subject' => $subject, 'message' => $message)))
            {
                $context->getResponse()->addMessage(JText::_('ERROR_SENDING_CONFIRMATION_MAIL'), 'notice');
            }
        }
    }
}