<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Message Controller
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Contacts
 */
class ContactsControllerMessage extends Library\ControllerView
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array('behaviors' => 'captchable'));
        parent::_initialize($config);
    }

    protected function _actionAdd(Library\CommandContext $context)
	{
        // Get data from form
        $data        = $context->request->data;
	    $name        = $data->get('name', 'string');
	    $email_from  = $data->get('email', 'email');
	    $body        = $data->get('text', 'string');
	    $subject     = $data->get('subject', 'string');

        // Get configuration values
	    $application = $this->getObject('application');
        $mail_from   = $application->getCfg('mailfrom');
        $from_name   = $application->getCfg('fromname');

        // Create body text
        $prefix      = JText::sprintf('This is an enquiry e-mail via %s from', $context->request->getBaseUrl());
        $body        = $prefix.' '.$name.' <'.$email_from.'>.'."\r\n\r\n".stripslashes($body);

        // Get recipient
        $email_to = $this->getObject('com:contacts.model.contacts')
            ->id($context->request->query->get('id', 'int'))
            ->getRow()
            ->email_to;

        // Send mail
        $mail = JFactory::getMailer();
        $mail->addRecipient($email_to);
        $mail->setSender(array($email_from, $name));
        $mail->setSubject($from_name.': '.$subject);
        $mail->setBody($body);
        $mail->Send();

        // Send copy if requested
        if($data->get('email_copy', 'boolean'))
        {
            $mail = JFactory::getMailer();
            $mail->addRecipient($email_from);
            $mail->setSender(array($mail_from, $from_name));
            $mail->setSubject($subject);
            $mail->setBody($body);
            $mail->Send();
        }

        $context->response->addMessage(JText::_('Thank you for your e-mail'));
        $context->response->setStatus(self::STATUS_RESET);
	}
}