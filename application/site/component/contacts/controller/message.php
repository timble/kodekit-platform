<?php
/**
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;

/**
 * Message Controller
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Contacts
 */
class ContactsControllerMessage extends Library\ControllerView
{ 
    protected function _actionAdd(Library\CommandContext $context)
	{
        // Set parts of the mail.
        $data        = $context->request->data;
	    $name        = $data->get('name', 'string');
	    $email_from  = $data->get('email', 'email');
	    $body        = $data->get('text', 'string');
	    $subject     = $data->get('subject', 'string');

	    $application = $this->getObject('application');
        $site_name   = $application->getCfg('sitename');

        $prefix      = JText::sprintf('This is an enquiry e-mail via %s from', $context->request->getBaseUrl());
        $body        = $prefix.' '.$name.' <'.$email_from.'>.'."\r\n\r\n".stripslashes($body);
        $mail_from   = $application->getCfg('mailfrom');
        $from_name   = $application->getCfg('fromname');

        $email_to = $this->getObject('com:contacts.model.contacts')
            ->id($context->request->query->get('id', 'int'))
            ->getRow()
            ->email_to;

        // Send mail.
        $mail = JFactory::getMailer();
        $mail->addRecipient($email_to);
        $mail->setSender(array($name, $email_from));
        $mail->setSubject($from_name.': '.$subject);
        $mail->setBody($body);
        $mail->Send();

        // Send copy if requested.
        if($data->get('email_copy', 'boolean'))
        {
            $copy_text    = JText::sprintf('Copy of:', $name, $site_name)."\r\n\r\n".$body;
            $copy_subject = JText::_('Copy of:').' '.$subject;

            $mail = JFactory::getMailer();
            $mail->addRecipient( $email_from );
            $mail->setSender( array( $from_name, $mail_from ) );
            $mail->setSubject( $copy_subject );
            $mail->setBody( $copy_text );
            $mail->Send();
        }

        $context->user->addFlashMessage(JText::_('Thank you for your e-mail'));
        $context->response->setStatus(self::STATUS_RESET);
	}
}