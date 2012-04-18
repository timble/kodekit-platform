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
 * Verifiable controller behavior class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Users
 */

class ComUsersControllerBehaviorVerifiable extends ComUsersControllerBehaviorSpammable
{
    /**
     * A list of checks that positively indentify a client as a spammer. If one of this checks
     * fail, registration will be denied.
     *
     * @var array A list of checks.
     */
    protected $_deny_checks;

    /**
     * Re-direction messages.
     *
     * @var KConfig The messages.
     */
    protected $_messages;

    public function __construct(KConfig $config = null) 
    {
        if (!$config) {
            $config = new KConfig();
        }

        parent::__construct($config);

        $this->_deny_checks = $config->deny_checks->toArray();
        $this->_messages    = $config->messages;
    }

    protected function _initialize(KConfig $config) 
    {
        $config->append(array(
            'checks'          => array(
                'com://admin/users.filter.spam.honeypot',
                'com://admin/users.filter.spam.reversehoneypot',
                'com://admin/users.filter.spam.timestamp',
                'com://admin/users.filter.spam.identicalvalues',
                'com://admin/users.filter.spam.useragent',
                'com://admin/users.filter.spam.referrer',
                'com://admin/users.filter.spam.blackhost',
                'com://admin/users.filter.spam.service.spamhaus',
                'com://admin/users.filter.spam.mxrecord',
                'com://admin/users.filter.spam.blacklist'
            ),
            'deny_checks'     => array(
                'com://admin/users.filter.spam.service.spamhaus',
                'com://admin/users.filter.spam.service.honeypot',
                'com://admin/users.filter.spam.service.botscout',
                'com://admin/users.filter.spam.mxrecord',
                'com://admin/users.filter.spam.blacklist'),
            'messages'        => array(
                'spammer' => 'You have been identified as a spammer or spambot. Please contact us for more information.',
                'captcha' => 'Please insert the code below and hit submit for completing the registration process')));
        
        parent::_initialize($config);
    }

    protected function _afterControllerSave(KCommandContext $context) 
    {
        // Nothing to do.
        if (!$this->isDispatched() || $this->whiteIp() || !$this->spammed(array('post' => $context->data))) {
            return true;
        }

        if ($this->spamChecksFailed($this->_deny_checks)) 
        {
            // Blacklist client.
            $this->blacklist($context->data);

            // Re-direct user to default page with message corresponding message.
            $url = JRoute::_('index.php?Itemid=' . JSite::getMenu()->getDefault()->id, false);
            $this->setRedirect($url, JText::_($this->_messages->spammer), 'notice');
        } 
        else 
        {
            // Encrypt password and password_verify.
            $password_helper                = $this->getService('com://admin/users.helper.password');
            $salt                           = $password_helper->getRandom(32);
            $context->data->password        = $password_helper->encrypt($context->data->password, $salt);
            $context->data->password_verify = $password_helper->encrypt($context->data->password_verify, $salt);

            // Temporarily store current user data and re-direct user to a captcha check.
            KRequest::set('session.com.users.controller.user.data', $context->data->toArray());
            $this->setRedirect(JRoute::_('index.php?option=com_users&view=captcha', false),
                JText::_($this->_messages->captcha), 'notice');
        }

        // Break the command chain for making sure that no more re-directions will be set.
        return false;
    }
}