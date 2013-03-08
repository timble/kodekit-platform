<?php
/**
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Message Controller
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Contacts
 */
class ComContactsControllerMessage extends ComDefaultControllerView
{ 
    protected function _actionAdd(Framework\CommandContext $context)
	{
        // Set parts of the mail.
        $data        = $context->request->data;
	    $name        = $data->get('name', 'string');
	    $email_from  = $data->get('email', 'email');
	    $body        = $data->get('text', 'string');
	    $subject     = $data->get('subject', 'string');

	    $application = $this->getService('application');
        $site_name   = $application->getCfg('sitename');

        $prefix      = JText::sprintf('ENQUIRY_TEXT', JURI::base());
        $body        = $prefix."\n".$name.' <'.$email_from.'>'."\r\n\r\n".stripslashes($body);
        $mail_from   = $application->getCfg('mailfrom');
        $from_name   = $application->getCfg('fromname');

        $email_to = $this->getService('com://admin/contacts.model.contacts')
            ->id($context->request->query->get('id', 'int'))
            ->getRow()
            ->email_to;

        // Send mail.
        $mail = JFactory::getMailer();
        $mail->addRecipient($email_to);
        $mail->setSender(array($email_from, $name));
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
            $mail->setSender( array( $mail_from, $from_name ) );
            $mail->setSubject( $copy_subject );
            $mail->setBody( $copy_text );
            $mail->Send();
        }

	    $message = JText::_('Thank you for your e-mail');
	    $context->response->setRedirect($context->request->getReferrer(), $message);
	}

    public function __call($method, $args)
    {
        if($method == 'id')
        {
            $this->getView()->id = $args[0];
            return $this;
        }

        return parent::__call($method, $args);
    }
}