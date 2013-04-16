<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright      Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git
 */

use Nooku\Library, Nooku\Component\Users;

class UserControllerBehaviorResettable extends Users\ControllerBehaviorResettable
{
    protected function _afterControllerToken(Library\CommandContext $context)
    {
        if ($context->result) {
            $url = $this->getService('application.pages')->getHome()->getLink();
            $this->getService('application')->getRouter()->build($url);
            $context->response->setRedirect($url);
            //@TODO : Set message in session
            //$context->response->setRedirect($url, \JText::_('CONFIRMATION_EMAIL_SENT'));
        }
    }
}