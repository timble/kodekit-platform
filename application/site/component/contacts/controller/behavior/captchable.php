<?php
/**
 * @package        Nooku_Server
 * @subpackage     Contacts
 * @copyright      Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Library, Nooku\Component\Users;


/**
 * Captchable Controller Behavior.
 *
 * @author        Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package       Nooku_Server
 * @subpackage    Contacts
 */
class ContactsControllerBehaviorCaptchable extends Users\ControllerBehaviorCaptchable
{
    protected function _beforeControllerRender(Library\CommandContext $context)
    {
        $session = $context->user->getSession();
        if ($session->isActive())
        {
            $container = $session->getContainer('captcha');
            if ($container->has('data'))
            {
                // Push data to the view.
                $this->getView()->captcha_data = $container->get('data');
                // Cleanup.
                $container->clear();
            }
        }
    }

    protected function _beforeControllerAdd(Library\CommandContext $context)
    {
        $result = parent::_beforeControllerAdd($context);

        if (!$result)
        {
            $context->user->getSession()->getContainer('captcha')->set('data', $context->request->getData());
            $context->response->setRedirect($context->request->getReferrer(), $this->getCaptchaErrorMessage(), 'error');
        }

        return $result;
    }
}