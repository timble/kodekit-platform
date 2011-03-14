<?php
/**
 * @version     $Id: koowa.php 2775 2011-01-01 17:02:39Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Plugins
 * @subpackage  System
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Koowa System plugin
.*
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Nooku
 * @package     Nooku_Plugings
 * @subpackage  System
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgSystemKoowa extends JPlugin
{
	public function __construct($subject, $config = array())
	{
		// Check if Koowa is active
		if(JFactory::getApplication()->getCfg('dbtype') != 'mysqli')
		{
    		JError::raiseWarning(0, JText::_("Koowa plugin requires MySQLi Database Driver. Please change your database configuration settings to 'mysqli'"));
    		return;
		}
		
		// Check for suhosin
		if(in_array('suhosin', get_loaded_extensions()))
		{
			//Attempt setting the whitelist value
			@ini_set('suhosin.executor.include.whitelist', 'tmpl://, file://');

			//Checking if the whitelist is ok
			if(!@ini_get('suhosin.executor.include.whitelist') || strpos(@ini_get('suhosin.executor.include.whitelist'), 'tmpl://') === false)
			{
				JError::raiseWarning(0, sprintf(JText::_('Your server has Suhosin loaded. Please follow <a href="%s" target="_blank">this</a> tutorial.'), 'https://nooku.assembla.com/wiki/show/nooku-framework/Known_Issues'));
				return;
			}
		}
		
		//Set constants
		define('KDEBUG', JDEBUG);
		
		//Set exception handler
		set_exception_handler(array($this, 'exceptionHandler'));
		
		// Require the library loader
		JLoader::import('libraries.koowa.koowa', JPATH_ROOT);
		JLoader::import('libraries.koowa.loader.loader', JPATH_ROOT);
		
		//Instantiate the singletons
		KLoader::instantiate();
		KFactory::instantiate();
		KRequest::instantiate();
	
		//Add loader adapters
		KLoader::addAdapter(new KLoaderAdapterJoomla());
		KLoader::addAdapter(new KLoaderAdapterModule());
		KLoader::addAdapter(new KLoaderAdapterPlugin());
        KLoader::addAdapter(new KLoaderAdapterComponent());
            
		//Add factory adapters
		KFactory::addAdapter(new KFactoryAdapterJoomla());
		KFactory::addAdapter(new KFactoryAdapterModule());
		KFactory::addAdapter(new KFactoryAdapterPlugin());
		KFactory::addAdapter(new KFactoryAdapterComponent());
		
		//Set the root path for the request based on the application name
        KRequest::root(str_replace('/'.JFactory::getApplication()->getName(), '', KRequest::base()));
		
		//Create the koowa database object
		$dbo = JFactory::getDBO();
		
		$resource = method_exists($dbo, 'getConnection') ? $dbo->getConnection() : $dbo->_resource;
		$prefix   = method_exists($dbo, 'getPrefix')     ? $dbo->getPrefix()     : $dbo->_table_prefix;
		
		$db	= KFactory::get('lib.koowa.database.adapter.mysqli')
				->setConnection($resource)
				->setTablePrefix($prefix);
		
        //Set factory identifier aliasses
        KFactory::map('lib.koowa.database'   , $db);
        KFactory::map('lib.koowa.application', 'lib.joomla.application');
        KFactory::map('lib.koowa.language'   , 'lib.joomla.language');
        KFactory::map('lib.koowa.document'   , 'lib.joomla.document');
        KFactory::map('lib.koowa.user'       , 'lib.joomla.user');
	    KFactory::map('lib.koowa.editor'     , 'lib.joomla.editor');
        
	  	//If the format is AJAX we create a 'raw' document rendered and force it's type to the active format 
        //if the format is 'html' or if the tmpl is empty.
        if(KRequest::type() == 'AJAX') 
        {
        	if(KRequest::get('get.format', 'cmd', 'html') != 'html' || KRequest::get('get.tmpl', 'cmd') === '')
        	{
        		$format = JRequest::getWord('format', 'html');
        	
        		JRequest::setVar('format', 'raw');   //force format to raw
        		JFactory::getDocument()->setType($format);
        		JRequest::setVar('format', $format); //revert format to original
        	}
        }
        
		//Load the koowa plugins
		JPluginHelper::importPlugin('koowa', null, true, KFactory::get('lib.koowa.event.dispatcher'));
		
	    //Bugfix : Set offset accoording to user's timezone
		if(!KFactory::get('lib.koowa.user')->guest) 
		{
		   if($offset = KFactory::get('lib.koowa.user')->getParam('timezone')) {
		        KFactory::get('lib.joomla.config')->setValue('config.offset', $offset);
		   }
		}
		
		parent::__construct($subject, $config = array());
	}
	
	/**
	 * On after intitialse event handler
	 * 
	 * This functions implements HTTP Basic authentication support
	 * 
	 * @return void
	 */
	public function onAfterInitialise()
	{  
	    if(KRequest::has('server.PHP_AUTH_USER') && KRequest::has('server.PHP_AUTH_PW')) 
	    {
	        $credentials = array(
	            'username' => KRequest::get('server.PHP_AUTH_USER', 'url'),
	            'password' => KRequest::get('server.PHP_AUTH_PW'  , 'url'),
	        );
	        
	        if(KFactory::get('lib.koowa.application')->login($credentials) !== true) 
	        {  
	            throw new KException('Login failed', KHttp::UNAUTHORIZED);
        	    return false;      
	        }
	        
	        //Force the token
	        KRequest::set('request._token', JUtility::getToken());
	    }
	}
	
 	/**
	 * Catch all exception handler
	 *
	 * Calls the Joomla error handler to process the exception
	 *
	 * @param object an Exception object
	 * @return void
	 */
	public function exceptionHandler($exception)
	{
		$this->_exception = $exception; //store the exception for later use
		
		//Change the Joomla error handler to our own local handler and call it
		JError::setErrorHandling( E_ERROR, 'callback', array($this,'errorHandler'));
		JError::raiseError($exception->getCode(), $exception->getMessage());
	}

	/**
	 * Custom JError callback
	 *
	 * Push the exception call stack in the JException returned through the call back
	 * adn then rener the custom error page
	 *
	 * @param object A JException object
	 * @return void
	 */
	public function errorHandler($error)
	{
		$error->setProperties(array(
			'backtrace'	=> $this->_exception->getTrace(),
			'file'		=> $this->_exception->getFile(),
			'line'		=> $this->_exception->getLine()
		));
		
		if(KFactory::get('lib.joomla.config')->getValue('config.debug')) {
			$error->set('message', (string) $this->_exception);
		} else {
			$error->set('message', $this->_exception->getMessage());
		}
		
		if($this->_exception->getCode() == KHttp::UNAUTHORIZED) {
		   header('WWW-Authenticate: Basic Realm="'.KRequest::base().'"');
		}
		
		//Make sure the buffers are cleared
		while(@ob_get_clean());
		JError::customErrorPage($error);
	}
}

/**
 * PHP5.3 compatibility
 */
if(false === function_exists('lcfirst'))
{
    /**
     * Make a string's first character lowercase
     *
     * @param string $str
     * @return string the resulting string.
     */
    function lcfirst( $str ) {
        $str[0] = strtolower($str[0]);
        return (string)$str;
    }
}