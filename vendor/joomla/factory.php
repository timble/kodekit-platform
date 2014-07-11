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
defined('JPATH_BASE') or die();
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
     * Get a configuration object
     *
     * Returns a reference to the global {@link JRegistry} object, only creating it
     * if it doesn't already exist.
     *
     * @access public
     * @param string    The path to the configuration file
     * @param string    The type of the configuration file
     * @return object JRegistry
     */
    function &getConfig($file = null, $type = 'PHP')
    {
        static $instance;

        if (!is_object($instance)) {
            $instance = JFactory::_createConfig($file, $type);
        }

        return $instance;
    }

    /**
     * Get a language object
     *
     * Returns a reference to the global {@link JLanguage} object, only creating it
     * if it doesn't already exist.
     *
     * @access public
     * @return object JLanguage
     */
    function &getLanguage()
    {
        static $instance;

        if (!is_object($instance)) {
            //get the debug configuration setting
            $conf =& JFactory::getConfig();
            $debug = $conf->getValue('config.debug_lang');

            $instance = JFactory::_createLanguage();
            $instance->setDebug($debug);
        }

        return $instance;
    }

    /**
     * Get a cache object
     *
     * Returns a reference to the global {@link JCache} object
     *
     * @access public
     * @param string The cache group name
     * @param string The handler to use
     * @param string The storage method
     * @return object JCache
     */
    function &getCache($group = '', $handler = 'callback', $storage = null)
    {
        $handler = ($handler == 'function') ? 'callback' : $handler;

        $conf =& JFactory::getConfig();

        if (!isset($storage)) {
            $storage = $conf->getValue('config.cache_handler', 'file');
        }

        $options = array(
            'defaultgroup' => $group,
            'cachebase' => $conf->getValue('config.cache_path'),
            'lifetime' => $conf->getValue('config.cachetime') * 60, // minutes to seconds
            'language' => $conf->getValue('config.language'),
            'storage'  => $storage,
            'site'     => Nooku\Library\ObjectManager::getInstance()->getObject('application')->getSite()
        );

        jimport('joomla.cache.cache');

        $cache =& JCache::getInstance($handler, $options);
        $cache->setCaching($conf->getValue('config.caching'));
        return $cache;
    }

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
     * Create a configuration object
     *
     * @access private
     * @param string    The path to the configuration file
     * @param string    The type of the configuration file
     * @return object JRegistry
     * @since 1.5
     */
    function &_createConfig($file, $type = 'PHP')
    {
        jimport('joomla.registry.registry');

        if($file !== null) {
            require_once $file;
        }

        // Create the registry with a default namespace of config
        $registry = new JRegistry('config');
        return $registry;
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

        $conf =& JFactory::getConfig();

        $sendmail = $conf->getValue('config.sendmail');
        $smtpauth = $conf->getValue('config.smtpauth');
        $smtpuser = $conf->getValue('config.smtpuser');
        $smtppass = $conf->getValue('config.smtppass');
        $smtphost = $conf->getValue('config.smtphost');
        $smtpsecure = $conf->getValue('config.smtpsecure');
        $smtpport = $conf->getValue('config.smtpport');
        $mailfrom = $conf->getValue('config.mailfrom');
        $fromname = $conf->getValue('config.fromname');
        $mailer = $conf->getValue('config.mailer');

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

    /**
     * Create a language object
     *
     * @access private
     * @return object JLanguage
     * @since 1.5
     */
    function &_createLanguage()
    {
        jimport('joomla.language.language');

        //Get the active language
        $languages  = $this->getObject('application.languages');
        $active     = $languages->getActive()->iso_code;

        $conf =& JFactory::getConfig();
        $lang =& JLanguage::getInstance($active);
        $lang->setDebug($conf->getValue('config.debug_lang'));

        return $lang;
    }
}
