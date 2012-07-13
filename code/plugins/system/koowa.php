<?php
/**
 * @version     $Id: koowa.php 2775 2011-01-01 17:02:39Z johanjanssens $
 * @package     Nooku_Plugins
 * @subpackage  System
 * @copyright  	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Koowa System plugin
.*
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Nooku_Plugins
 * @subpackage  System
 */

class plgSystemKoowa extends PlgKoowaDefault
{
	public function __construct($config = array())
	{
		// Command line fixes for Joomla
		if (PHP_SAPI === 'cli') 
		{
			if (!isset($_SERVER['HTTP_HOST'])) {
				$_SERVER['HTTP_HOST'] = '';
			}
			
			if (!isset($_SERVER['REQUEST_METHOD'])) {
				$_SERVER['REQUEST_METHOD'] = '';
			}
		}

		//Suhosin compatibility
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
		
		//Safety Extender compatibility
		if(extension_loaded('safeex') && strpos('tmpl', ini_get('safeex.url_include_proto_whitelist')) === false)
		{
		    $whitelist = ini_get('safeex.url_include_proto_whitelist');
		    $whitelist = (strlen($whitelist) ? $whitelist . ',' : '') . 'tmpl';
		    ini_set('safeex.url_include_proto_whitelist', $whitelist);
 		}

	    //Bugfix : Set offset accoording to user's timezone
		if(!JFactory::getUser()->guest) 
		{
		   if($offset = JFactory::getUser()->getParam('timezone')) {
		        JFactory::getConfig()->setValue('config.offset', $offset);
		   }
		}
		
		parent::__construct($config);
	}
	
	/**
	 * On after intitialse event handler
	 * 
	 * This functions implements HTTP Basic authentication support
	 * 
	 * @return void
	 */
	public function onBeforeControllerRoute(KEvent $event)
	{  
	     /*
	     * Try to log the user in 
	     * 
	     * If the request contains authorization information we try to log the user in
	     */
	    if($this->_params->get('auth_basic', 1) && JFactory::getUser()->get('guest')) {
	        $this->_authenticateUser();
	    }
	    
	    /*
	     * Dispatch the default dispatcher 
	     *
	     * If we are running in CLI mode bypass the default Joomla executition chain and dispatch the default
	     * dispatcher.
	     */
	    if (PHP_SAPI === 'cli') 
	    {
	    	$url = null;
	    	foreach ($_SERVER['argv'] as $arg) 
	    	{
	    		if (strpos($arg, '--url') === 0) 
	    		{
	    			$url = str_replace('--url=', '', $arg);
	    			if (strpos($url, '?') === false) {
	    				$url = '?'.$url;
	    			}
	    			break; 
	    		}
	    	}
	    	
	    	if (!empty($url)) 
	    	{
	    		$component = 'default';
	    		$url = KService::get('koowa:http.url', array('url' => $url));
    			if (!empty($url->query['option'])) {
    				$component = substr($url->query['option'], 4);
    			}

	    		// Thanks Joomla. We will take it from here.
	    		echo KService::get('com:'.$component.'.dispatcher.cli')->dispatch();
	    		exit(0);	
	    	}
	    }
	}
	
	/**
	 * On after route event handler
	 * 
	 * @return void
	 */
	public function onAfterControllerRoute(KEvent $event)
	{      
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
        
        //Set the request format
        if(!KRequest::has('request.format')) {
            KRequest::set('request.format', KRequest::format());
        }
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
	    /*if(KRequest::has('server.PHP_AUTH_USER') && KRequest::has('server.PHP_AUTH_PW'))
	    {
	        $credentials = array(
	            'username' => KRequest::get('server.PHP_AUTH_USER', 'url'),
	            'password' => KRequest::get('server.PHP_AUTH_PW'  , 'url'),
	        );
	        
	        if(JFactory::getApplication()->login($credentials) !== true) 
	        {  
	            throw new KException('Login failed', KHttpResponse::UNAUTHORIZED);
        	    return false;      
	        }
	           
	        //Force the token
	        KRequest::set('request._token', JUtility::getToken());
	        
	        return true;
	    }*/
	    
	    return false;
	}
}