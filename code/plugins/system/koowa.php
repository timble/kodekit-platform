<?php
/**
 * @version     $Id: koowa.php 2050 2010-05-15 20:30:30Z johanjanssens $
 * @category	Koowa
 * @package     Koowa_Plugins
 * @subpackage  System
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
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
		// Check if Koowa is active
		if(JFactory::getApplication()->getCfg('dbtype') != 'mysqli')
		{
    		JError::raiseWarning(0, JText::_("Koowa plugin requires MySQLi Database Driver. Please change your database configuration settings to 'mysqli'"));
    		return;
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
		
		// Decorate the application object
		$app  =& JFactory::getApplication();
		$app  = new KDecoratorJoomlaApplication($app);
		
		//Create the koowa database object
		$db  = KFactory::get('lib.koowa.database.adapter.mysqli')
			->setConnection(JFactory::getDBO()->_resource)
			->setTablePrefix(JFactory::getDBO()->_table_prefix);
		
        //Set factory identifier aliasses
        KFactory::map('lib.koowa.database'   , $db);
        KFactory::map('lib.koowa.application', 'lib.joomla.application');
        KFactory::map('lib.koowa.language'   , 'lib.joomla.language');
        KFactory::map('lib.koowa.user'       , 'lib.joomla.user');
        
        //Send a header that tells Nooku Desktop that this is Nooku Server
        JResponse::setHeader('x-nooku-desktop', 'version=1.0;');
        
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

		parent::__construct($subject, $config = array());
	}
	
	/**
	 * Prettify the output using Tidy filter (if available) and debug has been
	 * enabled
	 *
	 * @return void
	 */
	public function onAfterRender()
	{
		/*if(KDEBUG)
		{
			$config =  array(
					'indent'            => true,
                	'indent-attributes' => true,
                	'wrap'              => 120,
			);
	
			$filter = new KFilterTidy(array('config' => $config));
			$result = $filter->sanitize(JResponse::getBody());
		
			JResponse::setBody($result);
		}*/
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
		JError::raiseError('500', $exception->getMessage());
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
		$error->backtrace = $this->_exception->getTrace();
		$error->file      = $this->_exception->getFile();
		$error->line      = $this->_exception->getLine();
		
		if(KFactory::get('lib.joomla.config')->getValue('config.debug')) {
			$error->message   = $this->_exception;
		} else {
			$error->message   = '';
		}
		
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