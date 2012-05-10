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
 * User spammable controller behavior class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Users
 */

class ComUsersControllerBehaviorUserSpammable extends ComUsersControllerBehaviorSpammable
{
    protected function _initialize(KConfig $config) {

        $parameters = JComponentHelper::getParams('com_users');

        if ($parameters->get('spam_checks', false)) {
            $config->append(array(
                'checks'          => array(
                    'com://admin/users.filter.spam.composite.positive',
                    'com://admin/users.filter.spam.composite.default')));
        }
        parent::_initialize($config);
    }

    protected function _afterControllerSave(KCommandContext $context) {

        if (!$this->isDispatched() || $this->whiteIp() || !$this->spammed(array('post' => $context->data))) {
            // HVMC, whitelisted or not spammed => Nothing to do.
            return true;
        }

        if (strpos($this->getFailedCheck(), 'positive')) {
            // User positively identified as a spammer.

            // Blacklist client.
            $this->blacklist($context->data);

            // Re-direct user to default page with message corresponding message.
            $url = JRoute::_('index.php?Itemid=' . JSite::getMenu()->getDefault()->id, false);
            $this->setRedirect($url, JText::_($this->_error_msg), 'notice');
        }
        else
        {
            // User identified as potential spammer.

            // Encrypt password and password_verify.
            $password_helper                = $this->getService('com://admin/users.helper.password');
            $salt                           = $password_helper->getRandom(32);
            $context->data->password        = $password_helper->encrypt($context->data->password, $salt);
            $context->data->password_verify = $password_helper->encrypt($context->data->password_verify, $salt);

            // Temporarily store current user data and re-direct user to a captcha check.
            KRequest::set('session.com.users.controller.user.data', $context->data->toArray());
            $this->setRedirect(JRoute::_('index.php?option=com_users&view=captcha', false),
                JText::_('Please insert the code below and hit submit for completing the registration process'),
                'notice');
        }

        // Break the command chain for making sure that no more re-directions will be set.
        return false;
    }
}