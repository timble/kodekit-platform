<?php
/**
* @version 		$Id$
* @category		Koowa
* @package 		Koowa_Decorator
* @subpackage 	Joomla
* @copyright 	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
* @license 		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.koowa.org
*/
 
/**
 * Joomla Application Decorator
 *
 * @author 		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package 	Koowa_Decorator
 * @subpackage 	Joomla
 * @uses 		KMixinCommand
 * @uses 		KPatternDecorator
 */
class KDecoratorJoomlaApplication extends KPatternDecorator
{
	/**
	 * Constructor
	 *
	 * @param	object	The application object to decorate
	 * @return	void
	 */
	public function __construct($app)
	{
		parent::__construct($app);
		
		// Mixin the command chain
        $this->mixin(new KMixinCommandchain(array('mixer' => $this)));
            
     	//Set the root path for the request based on the application name
        KRequest::root(str_replace('/'.$this->_object->getName(), '', KRequest::base()));
	}
	
	/**
	 * Decorate the application getName() method
	 * 
	 * @return	string 	The application name
	 */
	function getName()
	{
		//Create a shortcut for the administrator name
		$name = $this->_object->getName();
		if($name == 'administrator') {
			$name = 'admin';
		}
		
		return $name;
	}
	
  	/**
	 * Decorate the application initialise() method
	 *
	 * @param	array An optional associative array of configuration settings
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
	public function initialise(array $options = array())
	{
		$context = new KCommandContext();
		$context['caller'] 	= $this;
		$context['options'] = $options;
		$context['action']  = 'initialise';
	
		if($this->getCommandChain()->run('application.before.initialise', $context) === true) {
			$context['result'] = $this->getObject()->initialise($context['options']);
			$this->getCommandChain()->run('application.after.initialise', $context);
		}

		return $context['result'];
	}
	
  	/**
	 * Decorate the application route() method
	 * 
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
	public function route()
 	{
		$context = new KCommandContext();
		$context['caller'] 	= $this;
		$context['action']   = 'route';
	
		if($this->getCommandChain()->run('application.before.route', $context) === true) {
			$context['result'] = $this->getObject()->route();
			$this->getCommandChain()->run('application.after.route', $context);
		}
		
		return $context['result'];
 	}
 	
   	/**
	 * Decorate the application dispatch() method
	 * 
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
 	public function dispatch($component)
 	{
		$context = new KCommandContext();
		$context['caller'] 	   = $this;
		$context['component']  = substr( $component, 4 );
		$context['action']     = 'dispatch';
		
		if($this->getCommandChain()->run('application.before.dispatch', $context) === true) {
			$context['result'] = $this->getObject()->dispatch('com_'.$context['component']);
			$this->getCommandChain()->run('application.after.dispatch', $context);
		}

		return $context['result'];
 	}
 	
	/**
	 * Decorate the application render() method
	 * 
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
	public function render()
	{
		$context = new KCommandContext();
		$context['caller'] = $this;
		$context['action'] = 'render';
		
		if($this->getCommandChain()->run('application.before.render', $context) === true) {
			$context['result'] = $this->getObject()->render();
			$this->getCommandChain()->run('application.after.render', $context);
		}

		return $context['result'];
	}
	
	/**
	 * Decorate the application close() method
	 *
	 * @param	int	Exit code
	 * @return	none|false The value returned by the proxied method, false in error case.
	 */
	public function close( $code = 0 ) 
	{
		$context = new KCommandContext();
		$context['caller'] 	 = $this;
		$context['code']	 = $code;
		$context['action']   = 'close';
		
		if($this->getCommandChain()->run('application.before.close', $context) === true) {
			$this->getObject()->close($context['code']);
		}

		return false;
	}
	
	/**
	 * Decorate the application redirect() method
	 *
	 * @param	string	The URL to redirect to.
	 * @param	string	An optional message to display on redirect.
	 * @param	string  An optional message type.
	 * @return	none|false The value returned by the proxied method, false in error case.
	 * @see		JApplication::enqueueMessage()
	 */
	public function redirect( $url, $msg = '', $type = 'message' )
	{
		$context = new KCommandContext();
		$context['caller'] 	 	 = $this;
		$context['url']          = $url;
		$context['message']      = $msg;
		$context['message_type'] = $type;
		$context['action']       = 'redirect';
		
		if($this->getCommandChain()->run('application.before.redirect', $context) === true) {
			$this->getObject()->redirect($context['url'], $context['message'], $context['message_type']);
		}

		return false;
	}
	
	/**
	 * Decorate the application login() method
	 *
	 * @param	array 	Array( 'username' => string, 'password' => string )
	 * @param	array 	Array( 'remember' => boolean )
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
	public function login($credentials, array $options = array())
	{
		$context = new KCommandContext();
		$context['caller']    	= $this;
		$context['credentials'] = $credentials;
		$context['options']     = $options;
		$context['action']      = 'login';
		
		if($this->getCommandChain()->run('application.before.login', $context) === true) {
			$context['result'] = $this->getObject()->login($context['credentials'], $context['options']);
			$this->getCommandChain()->run('application.after.login', $context);
		}
		
		return $context['result'];
	}
	
	/**
	 * Decorate the application logout() method
	 *
	 * @param 	int 	The user to load - Can be an integer or string - If string, it is converted to ID automatically
	 * @param	array 	Array( 'clientid' => array of client id's )
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
	public function logout($userid = null, array $options = array())
	{
		$context = new KCommandContext();
		$context['caller']    	= $this;
		$context['credentials'] = array('userid' => $userid);
		$context['options']     = $options;
		$context['action']      = 'logout';
		
		if($this->getCommandChain()->run('application.before.logout', $context) === true) {
			$context['result'] = $this->getObject()->logout($context['credentials']['userid'], $context['options']);
			$this->getCommandChain()->run('application.after.logout', $context);
		}
		
		return $context['result'];
	}
}