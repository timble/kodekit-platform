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
	 * @param	object	$dbo 	The application object to decorate
	 * @return	void
	 */
	public function __construct($app)
	{
		parent::__construct($app);
		
		// Mixin the command chain
        $this->mixin(new KMixinCommand($this));
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
		//Create the arguments object
		$args = new ArrayObject();
		$args['notifier'] = $this;
		$args['options']  = $options;
	
		if($this->getCommandChain()->run('application.before.initialise', $args) === true) {
			$args['result'] = $this->getObject()->initialise($args['options']);
			$this->getCommandChain()->run('application.after.initialise', $args);
		}

		return $args['result'];
	}
	
  	/**
	 * Decorate the application route() method
	 * 
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
	public function route()
 	{
		//Create the arguments object
		$args = new ArrayObject();
		$args['notifier'] = $this;
	
		if($this->getCommandChain()->run('application.before.route', $args) === true) {
			$args['result'] = $this->getObject()->route();
			$this->getCommandChain()->run('application.after.route', $args);
		}
		
		return $args['result'];
 	}
 	
   	/**
	 * Decorate the application dispatch() method
	 * 
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
 	public function dispatch($component)
 	{
		//Create the arguments object
		$args = new ArrayObject();
		$args['notifier']   = $this;
		$args['component']  = $component;
		
		if($this->getCommandChain()->run('application.before.dispatch', $args) === true) {
			$args['result'] = $this->getObject()->dispatch($args['component']);
			$this->getCommandChain()->run('application.after.dispatch', $args);
		}

		return $args['result'];
 	}
 	
	/**
	 * Decorate the application render() method
	 * 
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
	public function render()
	{
		//Create the arguments object
		$args = new ArrayObject();
		$args['notifier']     = $this;
		
		if($this->getCommandChain()->run('application.before.render', $args) === true) {
			$args['result'] = $this->getObject()->render();
			$this->getCommandChain()->run('application.after.render', $args);
		}

		return $args['result'];
	}
	
	/**
	 * Decorate the application close() method
	 *
	 * @param	int	Exit code
	 * @return	none|false The value returned by the proxied method, false in error case.
	 */
	public function close( $code = 0 ) 
	{
		//Create the arguments object
		$args = new ArrayObject();
		$args['notifier']   = $this;
		$args['code']		= $code;
		$args['action']     = 'close';
		
		if($this->getCommandChain()->run('application.before.execute', $args) === true) {
			$this->getObject()->close($args['code']);
		}

		return false;
	}
	
	/**
	 * Decorate the application redirect() method
	 *
	 * @param	string	$url	The URL to redirect to.
	 * @param	string	$msg	An optional message to display on redirect.
	 * @param	string  $msgType An optional message type.
	 * @return	none|false The value returned by the proxied method, false in error case.
	 * @see		JApplication::enqueueMessage()
	 */
	public function redirect( $url, $msg = '', $msgType = 'message' )
	{
		//Create the arguments object
		$args = new ArrayObject();
		$args['notifier']     = $this;
		$args['url']          = $url;
		$args['message']      = $msg;
		$args['message_type'] = $msgType;
		$args['action']       = 'redirect';
		
		if($this->getCommandChain()->run('application.before.redirect', $args) === true) {
			$this->getObject()->redirect($args['url'], $args['message'], $args['message_type']);
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
		//Create the arguments object
		$args = new ArrayObject();
		$args['notifier']    = $this;
		$args['credentials'] = $credentials;
		$args['options']     = $options;
		$args['action']        = 'login';
		
		if($this->getCommandChain()->run('application.before.login', $args) === true) {
			$args['result'] = $this->getObject()->login($args['credentials'], $args['options']);
			$this->getCommandChain()->run('application.after.login', $args);
		}
		
		return $args['result'];
	}
	
	/**
	 * Decorate the application logout() method
	 *
	 * @param 	int 	$userid   The user to load - Can be an integer or string - If string, it is converted to ID automatically
	 * @param	array 	$options  Array( 'clientid' => array of client id's )
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
	public function logout($userid = null, array $options = array())
	{
		//Create the arguments object
		$args = new ArrayObject();
		$args['notifier']    = $this;
		$args['credentials'] = array('userid' => $userid);
		$args['options']     = $options;
		$args['action']        = 'logout';
		
		if($this->getCommandChain()->run('application.before.logout', $args) === true) {
			$args['result'] = $this->getObject()->logout($args['credentials']['userid'], $args['options']);
			$this->getCommandChain()->run('application.after.logout', $args);
		}
		
		return $args['result'];
	}
}