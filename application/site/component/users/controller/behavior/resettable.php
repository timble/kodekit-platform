<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright      Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git
 */

use Nooku\Library, Nooku\Component\Users;

class UsersControllerBehaviorResettable extends Users\ControllerBehaviorResettable
{
    protected function _beforeControllerRead(Library\CommandContext $context)
    {
        if ($token = $context->request->query->get('token', $this->_filter))
        {
            // Push the token to the view.
            $this->getView()->token = $token;
        }
    }

    protected function _afterControllerToken(Library\CommandContext $context)
    {
        $user = $context->user;
        if (!$context->result)
        {
            $user->addFlashMessage(\JText::_('ERROR_SENDING_CONFIRMATION_EMAIL'), 'error');
            $url = $context->request->getReferrer();
        }
        else
        {
            $user->addFlashMessage(\JText::_('CONFIRMATION_EMAIL_SUCCESS'));
            $url = $this->getObject('application.pages')->getHome()->getLink();
            $this->getObject('application')->getRouter()->build($url);
        }
        $context->response->setRedirect($url);
    }

    protected function _afterControllerReset(Library\CommandContext $context)
    {
        if ($context->result)
        {
            $context->user->addFlashMessage(JText::_('PASSWORD_RESET_SUCCESS'));
            $url = $this->getObject('application.pages')->getHome()->getLink();
            $this->getObject('application')->getRouter()->build($url);
            $context->response->setRedirect($url);
        }
        else
        {
            $context->user->addFlashMessage($context->error, 'error');
            $context->response->setRedirect($context->request->getReferrer());
        }
    }
}