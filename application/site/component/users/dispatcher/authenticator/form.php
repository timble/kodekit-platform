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
 * Dispatcher form authenticator class.
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Component\Users
 */
class UsersDispatcherAuthenticatorForm extends Users\DispatcherAuthenticatorForm
{
    protected function _beforePost(Library\DispatcherContextInterface $context)
    {
        // Allow registration POST requests.
        if (!$this->_isRegistration($context))
        {
            parent::_beforePost($context);
        }
    }

    protected function _isRegistration(Library\DispatcherContextInterface $context)
    {
        $controller = $context->subject->getController();
        $identifier = $controller->getIdentifier();
        $state      = $controller->getModel()->getState();

        return (bool) (!$context->user->isAuthentic() && ($identifier->package == 'users') &&
            ($identifier->name == 'user') && ($context->action == 'post') && !$state->isUnique());
    }
}