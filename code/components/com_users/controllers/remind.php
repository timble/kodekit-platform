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
class ComUsersControllerRemind extends ComDefaultControllerPage
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

        $config = KFactory::get('lib.joomla.config');
        $url    = JRoute::_('index.php?option=com_user&view=login');

        $email = array(
            'from_email' => $config->getValue('mailfrom'),
            'from_name'  => $config->getValue('fromname'),
            'subject'    => JText::sprintf('USERNAME_REMINDER_EMAIL_TITLE', $config->getValue('sitename')),
            'body'		 => JText::sprintf('USERNAME_REMINDER_EMAIL_TEXT', $config->getValue('sitename'), $user->username, $url)
        );

        if(!JUtility::sendMail($email['from_email'], $email['from_name'], $email, $email['subject'], $email['body']))
		{
		    $this->setRedirect(KRequest::referrer(), JText::_('ERROR_SENDING_REMINDER_EMAIL'), 'error');
			return false;
		}

    }
}