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
    protected function _afterControllerToken(Library\CommandContext $context)
    {
        if ($context->result)
        {
            $url = $this->getObject('application.pages')->getHome()->getLink();
            $this->getObject('application')->getRouter()->build($url);

            $context->user->addFlashMessage(\JText::_('CONFIRMATION_EMAIL_SENT'));
            $context->response->setRedirect($url);
        }
    }
}