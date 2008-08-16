<?php
/**
* @version $Id:  $
* @package Koowa_Application
* @copyright Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
* @license GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link http://www.koowa.org
*/
 
/**
* Application Class
*
* @author Johan Janssens <johan@joomlatools.org>
* @package Koowa_Application
*/
class KApplicationAbstract extends KPatternProxy
{
	/**
	 * The commandchain
	 *
	 * @var	object
	 */
	protected $_commandChain = null;

	/**
	 * Constructor
	 *
	 * @param	object	$dbo 	The application object to proxy
	 * @return	void
	 */
	public function __construct($app)
	{
		parent::__construct($app);
		
		 //Create the command chain
        $this->_commandChain = new KPatternCommandChain();
	}
	
  	/**
	 * Initialise the application.
	 *
	 * @param	array An optional associative array of configuration settings
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
	public function initialise($options = array())
	{
		//Create the arguments object
		$args = new stdClass();
		$args->class_name = get_class($this);
		$args->options    = $options;
		$args->result     = false;
		
		if($this->_commandChain->execute('onBeforeApplicationInitialise', $args) === true) {
			$args->result = $this->getObject()->initialise($options);
			$this->_commandChain->execute('onAfterApplicationInitialise', $args);
		}

		return $args->result;
	}
	
  	/**
	 * Route the application.
	 * 
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
	public function route()
 	{
		//Create the arguments object
		$args = new stdClass();
		$args->class_name = get_class($this);
		$args->result     = false;
		
		if($this->_commandChain->execute('onBeforeApplicationRoute', $args) === true) {
			$args->result = $this->getObject()->route();
			$this->_commandChain->execute('onAfterApplicationRoute', $args);
		}

		return $args->result;
 	}
 	
   	/**
	 * Dispatch the applicaiton.
	 * 
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
 	public function dispatch($component)
 	{
		//Create the arguments object
		$args = new stdClass();
		$args->class_name = get_class($this);
		$args->component  = $component;
		$args->result     = false;
		
		if($this->_commandChain->execute('onBeforeApplicationDispatch', $args) === true) {
			$args->result = $this->getObject()->dispatch($component);
			$this->_commandChain->execute('onAfterApplicationDispatch', $args);
		}

		return $args->result;
 	}
 	
	/**
	 * Render the application.
	 * 
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
	public function render()
	{
		//Create the arguments object
		$args = new stdClass();
		$args->class_name = get_class($this);
		$args->result     = false;
		
		if($this->_commandChain->execute('onBeforeApplicationRender', $args) === true) {
			$args->result = $this->getObject()->render();
			$this->_commandChain->execute('onAfterApplicationRender', $args);
		}

		return $args->result;
	}
	
	/**
	 * Exit the application.
	 *
	 * @param	int	Exit code
	 * @return	none|false The value returned by the proxied method, false in error case.
	 */
	function close( $code = 0 ) 
	{
		//Create the arguments object
		$args = new stdClass();
		$args->class_name = get_class($this);
		
		if($this->_commandChain->execute('onBeforeApplicationExit', $args) === true) {
			$this->getObject()->close($code);
		}

		return false;
	}
	
	/**
	 * Redirect to another URL.
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
		$args = new stdClass();
		$args->class_name   = get_class($this);
		$args->url          = $url;
		$args->message      = $msg;
		$args->message_type = $msgType;
		
		if($this->_commandChain->execute('onBeforeApplicationRedirect', $args) === true) {
			$this->getObject()->redirect($url, $msg, $msgType);
		}

		return false;
	}
	
	/**
	 * Login authentication function.
	 *
	 * @param	array 	Array( 'username' => string, 'password' => string )
	 * @param	array 	Array( 'remember' => boolean )
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
	public function login($credentials, $options = array())
	{
		//Create the arguments object
		$args = new stdClass();
		$args->class_name  = get_class($this);
		$args->credentials = $credentials;
		$args->options     = $options;
		$args->result      = false;
		
		if($this->_commandChain->execute('onBeforeApplicationLogin', $args) === true) {
			$args->result = $this->getObject()->login($credentials, $options);
			$this->_commandChain->execute('onAfterApplicationLogin', $args);
		}
		
		return $args->result;
	}
	
	/**
	 * Logout authentication function.
	 *
	 * @param 	int 	$userid   The user to load - Can be an integer or string - If string, it is converted to ID automatically
	 * @param	array 	$options  Array( 'clientid' => array of client id's )
	 * @return	mixed|false The value returned by the proxied method, false in error case.
	 */
	public function logout($userid = null, $options = array())
	{
		//Create the arguments object
		$args = new stdClass();
		$args->class_name  = get_class($this);
		$args->credentials = array('userid' => $userid);
		$args->options     = $options;
		$args->result      = false;
		
		if($this->_commandChain->execute('onBeforeApplicationLogout', $args) === true) {
			$args->result = $this->getObject()->logout($userid, $options);
			$this->_commandChain->execute('onAfterApplicationLogout', $args);
		}
		
		return $args->result;
	}
}