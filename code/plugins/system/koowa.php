<?php
/**
 * @version     $Id:koowa.php 251 2008-06-14 10:06:53Z mjaz $
 * @category	Koowa
 * @package     Koowa_Plugins
 * @subpackage  System
 * @copyright   Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPLv2
 * @link        http://www.koowa.org
 */

/**
 * Koowa System plugin
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @category	Koowa
 * @package		Koowa
 */
class plgSystemKoowa extends JPlugin
{
	public function __construct($subject, $config = array())
	{
		if( self::canEnable()) 
		{	
			// Require the library loader
			JLoader::import('plugins.system.koowa.koowa', JPATH_ROOT);
			JLoader::import('plugins.system.koowa.loader', JPATH_ROOT);
			
			// Proxy the application object 
			$app  =& JFactory::getApplication();
			$app  = new KProxyJoomlaApplication($app);
		
			// Proxy the database object
			$db  =& JFactory::getDBO();
			$db  = new KDatabase($db);
			
			//ACL uses the unwrapped DBO
	        $acl = JFactory::getACL();
	        $acl->_db = $db->getObject(); // getObject returns the unwrapped DBO
			
			//Load the koowa plugins
			JPluginHelper::importPlugin('koowa', null, true, KFactory::get('lib.koowa.event.dispatcher'));
		}
		
		parent::__construct($subject, $config = array());
	}

	public function onAfterRoute()
	{
		if(self::canEnable()) 
		{	
			//Replace the document object
			$lang = KFactory::get('lib.joomla.language');
			
			$options = array (
				'charset'	=> 'utf-8',
				'language'	=> $lang->getTag(),
				'direction'	=> $lang->isRTL() ? 'rtl' : 'ltr'
			);
			
			$format = KInput::get('format', 'GET', 'word', 'word', 'html');
				
			$doc =& JFactory::getDocument();
			$doc = KFactory::get('lib.koowa.document.'.$format, $options);
		}
	}
	
	/**
	 * Check if the current request requires Koowa to be turned off
	 * 
	 * Eg. Koowa should be disabled when uninstalling plugins
	 *
	 * @return	bool
	 */
	public static function canEnable()
	{
		$result = true;
		
		// Note: can't use KInput, Koowa isn't loaded yet
		
		// are we uninstalling a plugin?
		if(JRequest::getCmd('option') == 'com_installer' 
			&& JRequest::getCmd('action') == 'remove'
			&& JRequest::getCmd('type') == 'plugins' ) {
			$result = false;
		}
		
		return $result;
	}
}