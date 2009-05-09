<?php
/**
 * @version     $Id:koowa.php 251 2008-06-14 10:06:53Z mjaz $
 * @category	Koowa
 * @package     Koowa_Plugins
 * @subpackage  System
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPLv2
 * @link        http://www.koowa.org
 */

/**
 * Koowa System plugin
 *
 * @author		Mathias Verraes <mathias@koowa.org>
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
			JLoader::import('plugins.system.koowa.loader.loader', JPATH_ROOT);
			
			//Add loader adapters
			KLoader::addAdapter(new KLoaderAdapterJoomla());
        	KLoader::addAdapter(new KLoaderAdapterComponent());
			
			//Add factory adapters
			KFactory::addAdapter(new KFactoryAdapterJoomla());
        	KFactory::addAdapter(new KFactoryAdapterComponent());
        		
			// Decorate the application object 
			$app  =& JFactory::getApplication();
			$app  = new KDecoratorJoomlaApplication($app);
			
			//Create the koowa database object
			$kdb = KFactory::get('lib.koowa.database', array('adapter' => 'mysqli'));
			
			// Decorate the database object
			$jdb  =& JFactory::getDBO();
			$jdb  = new KDecoratorJoomlaDatabase($jdb);
			
			// Create the koowa database object
			$kdb->setConnection($jdb->_resource);
			
			//ACL uses the unwrapped DBO
	        $acl = JFactory::getACL();
	        $acl->_db = $jdb->getObject(); // getObject returns the unwrapped DBO
			
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
			
			$format = KRequest::get('get.format', 'word', 'html');
				
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
		
		// Note: can't use KRequest, Koowa isn't loaded yet
		
		// Are we uninstalling a plugin?
		if(JRequest::getCmd('option') == 'com_installer' 
			&& JRequest::getCmd('action') == 'remove'
			&& JRequest::getCmd('type') == 'plugins' ) {
			$result = false;
		}
		
		return $result;
	}
}