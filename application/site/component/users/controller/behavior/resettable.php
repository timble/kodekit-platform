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
    protected function _beforeRead(Library\ControllerContext $context)
    {
        // Push the token to the view.
        if ($token = $context->request->query->get('token', $this->_filter)) {
            $this->getView()->token = $token;
        }
    }

    protected function _afterToken(Library\ControllerContext $context)
    {
        $user = $context->user;
        if (!$context->result)
        {
            $message = JText::_('ERROR_SENDING_CONFIRMATION_EMAIL');
            $type    = 'error';

            $url = $context->request->getReferrer();
        }
        else
        {
            $message = JText::_('CONFIRMATION_EMAIL_SUCCESS');
            $type    = 'success';

            $url = $this->getObject('application.pages')->getHome()->getLink();
            $this->getObject('application')->getRouter()->build($url);
        }
    }

    protected function _afterReset(Library\ControllerContext $context)
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