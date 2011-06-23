<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Remind Controller Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 */
class ComUsersControllerRemind extends ComDefaultControllerResource
{
    protected function _actionRemind(KCommandContext $context)
    {
        $email = KRequest::get('post.email', 'email');

        if(!KFactory::tmp('lib.koowa.filter.email')->validate($email))
        {
            $this->setRedirect(KRequest::referrer(), JText::_('INVALID_EMAIL_ADDRESS'), 'error');
            return false;
        }

        $user = KFactory::tmp('site::com.users.model.users')
            ->set('email', $email)
            ->getItem();

        if(!$user->id)
        {
            $this->setRedirect(KRequest::referrer(), JText::_('COULD_NOT_FIND_EMAIL'), 'error');
			return false;
        }

        $config     = KFactory::get('lib.joomla.config');
        $site_url   = KRequest::url()->get(KHttpUrl::SCHEME | KHttpUrl::HOST | KHttpUrl::PORT);
        $url        = $site_url.JRoute::_('index.php?option=com_users&view=login');

        $details = array(
            'from_email' => $config->getValue('mailfrom'),
            'from_name'  => $config->getValue('fromname'),
            'subject'    => JText::sprintf('USERNAME_REMINDER_EMAIL_TITLE', $config->getValue('sitename')),
            'body'		 => JText::sprintf('USERNAME_REMINDER_EMAIL_TEXT', $config->getValue('sitename'), $user->username, $url)
        );

        if(!JUtility::sendMail($details['from_email'], $details['from_name'], $email, $details['subject'], $details['body']))
		{
		    $this->setRedirect(KRequest::referrer(), JText::_('ERROR_SENDING_REMINDER_EMAIL'), 'error');
			return false;
		}

    }
}