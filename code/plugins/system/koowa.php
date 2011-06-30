<?php
/**
 * @version     $Id$
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
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Nooku
 * @package     Nooku_Plugins
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
		define('KDEBUG'      , JDEBUG);
		
		//Set path definitions
		define('JPATH_FILES' , JPATH_ROOT);
		define('JPATH_IMAGES', JPATH_ROOT.'/images');
		
		//Set exception handler
		set_exception_handler(array($this, 'exceptionHandler'));
		
		// Require the library loader
		JLoader::import('libraries.koowa.koowa', JPATH_ROOT);
		JLoader::import('libraries.koowa.loader.loader', JPATH_ROOT);
		
		 //Setup the loader
		KLoader::addAdapter(new KLoaderAdapterKoowa(Koowa::getPath()));
		KLoader::addAdapter(new KLoaderAdapterJoomla(JPATH_LIBRARIES));
		KLoader::addAdapter(new KLoaderAdapterModule(JPATH_BASE));
		KLoader::addAdapter(new KLoaderAdapterPlugin(JPATH_ROOT));
        KLoader::addAdapter(new KLoaderAdapterComponent(JPATH_BASE));
		
        //Setup the factory
		KFactory::addAdapter(new KFactoryAdapterKoowa());
		KFactory::addAdapter(new KFactoryAdapterJoomla());
		KFactory::addAdapter(new KFactoryAdapterModule());
		KFactory::addAdapter(new KFactoryAdapterPlugin());
		KFactory::addAdapter(new KFactoryAdapterComponent());
		
		//Setup the identifier application paths
		KIdentifier::registerApplication('site' , JPATH_SITE);
		KIdentifier::registerApplication('admin', JPATH_ADMINISTRATOR);
		
	    //Setup the request
        KRequest::root(str_replace('/'.JFactory::getApplication()->getName(), '', KRequest::base()));
			
        //Set factory identifier aliasses
        KFactory::map('lib.koowa.database.adapter.mysqli', 'admin::com.default.database.adapter.mysqli');
         
		//Load the koowa plugins
		JPluginHelper::importPlugin('koowa', null, true, KFactory::get('lib.koowa.event.dispatcher'));
		
	    //Bugfix : Set offset accoording to user's timezone
		if(!KFactory::get('lib.joomla.user')->guest) 
		{
		   if($offset = KFactory::get('lib.joomla.user')->getParam('timezone')) {
		        KFactory::get('lib.joomla.config')->setValue('config.offset', $offset);
		   }
		}

		parent::__construct($subject, $config);
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
	    /*
	     * Try to log the user in
	     * 
	     * If the request contains authorization information we try to log the user in
	     */
	    if($this->params->get('auth_basic', 0) && KFactory::get('lib.joomla.user')->get('guest')) {
	        $this->_authenticateUser();
	    }
	    
	    /*
	     * Reset the user and token
	     *
	     * In case another plugin have logged in after we initialized we need to reset the token and user object
	     * One plugin that could cause that, are the Remember Me plugin
	     */
	     if(KFactory::get('lib.joomla.user')->get('guest') && !JFactory::getUser()->get('guest'))
	     {
	         //Reset the user object in the factory
	         KFactory::set('lib.joomla.user', JFactory::getUser());
	          
	         //Force the token
	         KRequest::set('request._token', JUtility::getToken());
	     }
	    
	    /*
	     * Special handling for AJAX requests
	     * 
	     * If the format is AJAX and the format is 'html' or the tmpl is empty we re-create 
	     * a 'raw' document rendered and force it's type to the active format
	     */
        if(KRequest::type() == 'AJAX') 
        {
        	if(KRequest::get('get.format', 'cmd', 'html') != 'html' || KRequest::get('get.tmpl', 'cmd') === '')
        	{
        		$format = JRequest::getWord('format', 'html');
        	
        		JRequest::setVar('format', 'raw');   //force format to raw
        		
        		$document =& JFactory::getDocument();
        		$document = null;
        		JFactory::getDocument()->setType($format);
        		
        		JRequest::setVar('format', $format); //revert format to original
        	}
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
		
		//Make sure we have a valid status code
		JError::raiseError(KHttpResponse::isError($exception->getCode()) ? $exception->getCode() : 500, $exception->getMessage());
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
			$error->set('message', KHttpResponse::getMessage($error->code));
		}
		
	    if($this->_exception->getCode() == KHttpResponse::UNAUTHORIZED) {
		   header('WWW-Authenticate: Basic Realm="'.KRequest::base().'"');
		}
		
		//Make sure the buffers are cleared
		while(@ob_get_clean());
		
		JError::customErrorPage($error);
	}
	
	/**
	 * Basic authentication support
	 *
	 * This functions tries to log the user in if authentication credentials are
	 * present in the request.
	 *
	 * @return boolean	Returns TRUE is basic authentication was successful
	 */
	protected function _authenticateUser()
	{
	    if(KRequest::has('server.PHP_AUTH_USER') && KRequest::has('server.PHP_AUTH_PW')) 
	    {
	        $credentials = array(
	            'username' => KRequest::get('server.PHP_AUTH_USER', 'url'),
	            'password' => KRequest::get('server.PHP_AUTH_PW'  , 'url'),
	        );
	        
	        if(KFactory::get('lib.koowa.application')->login($credentials) !== true) 
	        {  
	            throw new KException('Login failed', KHttpResponse::UNAUTHORIZED);
        	    return false;      
	        }
	        
	        //Reset the user object in the factory
	        KFactory::set('lib.koowa.user', JFactory::getUser());
	         
	        //Force the token
	        KRequest::set('request._token', JUtility::getToken());
	        
	        return true;
	    }
	    
	    return false;
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