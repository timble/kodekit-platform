<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Registrable controller behavior class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Users
 */

class ComUsersControllerBehaviorRegistrable extends ComUsersControllerBehaviorCaptchable
{
    protected function _initialize(KConfig $config) 
    {
        $config->append(array(
            'private_key' => '',
            'public_key'  => ''
        ));
        
        parent::_initialize($config);
    }

    protected function _actionPost(KCommandContext $context) 
    {
        if ($this->captchaValid($context->data)) 
        {
            // Get the user data.
            $data = KRequest::get('session.com.users.controller.user.data', 'raw', null);
            if (is_null($data)) {
                throw new KControllerException('User data is missing');
            }

            // Create the user.
            $controller = $this->getService('com://site/users.controller.user');

            // Disable password encryption for user rows.
            $controller->getModel()->getItem()->setPasswordEncryption(false);
            $controller->save($data);

            // Unset session data.
            KRequest::set('session.com.users.controller.user.data', null);

            // Re-direct to the corresponding location (given by the user controller).
            $redirect = $controller->getRedirect();
            $this->setRedirect($redirect['url'], $redirect['message'], $redirect['type']);
        } 
        else $this->setRedirect(KRequest::referrer(), JText::_('Wrong captcha code, please try again'), 'error');
    }
}