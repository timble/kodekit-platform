<?php
/**
 * @version        $Id: factory.php 15184 2010-03-04 23:18:17Z ian $
 * @package        Joomla.Framework
 * @copyright    Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license        GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

/**
 * Joomla Framework Factory class
 *
 * @static
 * @package        Joomla.Framework
 * @since    1.5
 */
class JFactory
{
    /**
     * Get a mailer object
     *
     * Returns a reference to the global {@link JMail} object, only creating it
     * if it doesn't already exist
     *
     * @access public
     * @return object JMail
     */
    function &getMailer()
    {
        static $instance;

        if (!is_object($instance)) {
            $instance = JFactory::_createMailer();
        }

        $copy = clone($instance);

        return $copy;
    }

    /**
     * Get an XML document
     *
     * @access public
     * @param string The type of xml parser needed 'RSS' or 'Simple'
     * @param array:
     *         string  ['rssUrl'] the rss url to parse when using "RSS"
     *         string    ['cache_time'] with 'RSS' - feed cache time. If not defined defaults to 3600 sec
     * @return object Parsed XML document object
     */
    function &getXMLParser($type = 'Simple', $options = array())
    {
        $doc = null;

        switch (strtolower($type))
        {
            case 'simple' :
                jimport('joomla.utilities.simplexml');
                $doc = new JSimpleXML();
                break;

            default :
                $doc = null;
        }

        return $doc;
    }

    /**
     * Create a mailer object
     *
     * @access private
     * @return object JMail
     * @since 1.5
     */
    function &_createMailer()
    {
        jimport('joomla.mail.mail');

        $conf = Nooku\Library\ObjectManager::getInstance()->getObject('application')->getConfig();

        $sendmail = $conf->sendmail;
        $smtpauth = $conf->smtpauth;
        $smtpuser = $conf->smtpuser;
        $smtppass = $conf->smtppass;
        $smtphost = $conf->smtphost;
        $smtpsecure = $conf->smtpsecure;
        $smtpport = $conf->smtpport;
        $mailfrom = $conf->mailfrom;
        $fromname = $conf->fromname;
        $mailer = $conf->mailer;

        // Create a JMail object
        $mail =& JMail::getInstance();

        // Set default sender
        $mail->setSender(array($mailfrom, $fromname));

        // Default mailer is to use PHP's mail function
        switch ($mailer) {
            case 'smtp' :
                $mail->useSMTP($smtpauth, $smtphost, $smtpuser, $smtppass, $smtpsecure, $smtpport);
                break;
            case 'sendmail' :
                $mail->useSendmail($sendmail);
                break;
            default :
                $mail->IsMail();
                break;
        }

        return $mail;
    }
}
