<?php
/**
* @version 		$Id$
* @category		Koowa
* @package 		Koowa_Decorator
* @subpackage 	Joomla
* @copyright 	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
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
 * @uses 		KMixinCommandchain
 * @uses 		KPatternDecorator
 */
class KDecoratorJoomlaApplication extends KPatternDecorator
{
	/**
	 * The object identifier
	 *
	 * @var KIdentifierInterface
	 */
	protected $_identifier;
	
	/**
	 * Constructor
	 */
	public function __construct($app)
	{
		parent::__construct($app);
		
		//Set the identifier
		$this->_identifier = new KIdentifier('lib.koowa.application.'.$this->getName());
		
		// Mixin the command chain
        $this->mixin(new KMixinCommandchain(new KConfig(array('mixer' => $this, 'dispatch_events' => true))));
        
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
	 * Get the object identifier
	 * 
	 * @return	KIdentifier	
	 * @see 	KObjectIdentifiable
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}
	
  	/**
	 * Decorate the application initialise() method
	 *
	 * @param	array An optional associative array of configuration settings
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
	public function initialise(array $options = array())
	{
		$context = $this->getCommandContext();
		$context->options = $options;
		$context->action  = __FUNCTION__;
		
		if($this->getCommandChain()->run('before.initialise', $context) === true) {
			$context->result = $this->getObject()->initialise($context->option);
			$this->getCommandChain()->run('after.initialise', $context);
		}

		return $context->result;
	}
	
  	/**
	 * Decorate the application route() method
	 * 
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
	public function route()
 	{
		$context = $this->getCommandContext();
		$context->action   = __FUNCTION__;
		
		if($this->getCommandChain()->run('before.route', $context) === true) {
			$context->result = $this->getObject()->route();
			$this->getCommandChain()->run('after.route', $context);
		}
		
		return $context->result;
 	}
 	
   	/**
	 * Decorate the application dispatch() method
	 * 
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
 	public function dispatch($component)
 	{
		$context = $this->getCommandContext();
		$context->component  = substr( $component, 4 );
		$context->action   	 = __FUNCTION__;
		
		if($this->getCommandChain()->run('before.dispatch', $context) === true) {
			$context->results = $this->getObject()->dispatch('com_'.$context->component);
			$this->getCommandChain()->run('after.dispatch', $context);
		}

		return $context->result;
 	}
 	
	/**
	 * Decorate the application render() method
	 * 
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
	public function render()
	{
		$context = $this->getCommandContext();
		$context->action = __FUNCTION__;
		
		if($this->getCommandChain()->run('before.render', $context) === true) {
			$context->result = $this->getObject()->render();
			$this->getCommandChain()->run('after.render', $context);
		}

		return $context->result;
	}
	
	/**
	 * Decorate the application close() method
	 *
	 * @param	int	Exit code
	 * @return	none|false The value returned by the proxied method, false in error case.
	 */
	public function close( $code = 0 ) 
	{
		$context = $this->getCommandContext();
		$context->code	 = $code;
		$context->action = __FUNCTION__;
		
		if($this->getCommandChain()->run('before.close', $context) === true) {
			$this->getObject()->close($context->code);
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
		$context = $this->getCommandContext();
		$context->url          = $url;
		$context->message      = $msg;
		$context->message_type = $type;
		$context->action       = __FUNCTION__;
		
		if($this->getCommandChain()->run('before.redirect', $context) === true) {
			$this->getObject()->redirect($context->url, $context->message, $context->message_type);
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
		$context = $this->getCommandContext();
		$context->credentials = $credentials;
		$context->options     = $options;
		$context->action      = __FUNCTION__;
		
		if($this->getCommandChain()->run('before.login', $context) === true) {
			$context->result = $this->getObject()->login($context->credentials, $context->options);
			$this->getCommandChain()->run('after.login', $context);
		}
		
		return $context->result;
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
		$context = $this->getCommandContext();
		$context->credentials = array('userid' => $userid);
		$context->options     = $options;
		$context->action      = __FUNCTION__;
		
		if($this->getCommandChain()->run('before.logout', $context) === true) {
			$context->result = $this->getObject()->logout($context->credentials->userid, $context->options);
			$this->getCommandChain()->run('after.logout', $context);
		}
		
		return $context->result;
	}
}