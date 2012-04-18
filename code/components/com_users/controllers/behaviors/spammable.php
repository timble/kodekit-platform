<?php
/**
 * @version	 $Id$
 * @category	Nooku
 * @package	 Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license	 GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Spammable behavior
 *
 * @author Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category Nooku
 * @package Nooku_Server
 * @subpackage Users
 */
class ComUsersControllerBehaviorSpammable extends KControllerBehaviorAbstract
{
	/**
	 *
	 * @var array An array of spam checks to be executed.
	 */
	protected $_checks;

	/**
	 *
	 * @var array An array containing the checks that failed.
	 */
	protected $_failed_checks;

	/**
	 *
	 * @var boolean True is the form/data is spammed, false otherwise.
	 */
	protected $_spammed;

	/**
	 *
	 * @var string A secret string for generating hashs.
	 */
	protected $_secret;

	/**
	 *
	 * @var string The client IP.
	 */
	protected $_client_ip;

	/**
	 *
	 * @var boolean True if the client IP is whitelisted, false otherwise.
	 */
	protected $_white_ip;

	/**
	 *
	 * @var boolean True if the client IP is blacklisted, false otherwise.
	 */
	protected $_black_ip;

    /**
     * A message to be displayed on spammed JSON requests.
     *
     * @var string The message.
     */
    protected $_json_spammed_msg;

	public function __construct(KConfig $config = null)
	{
		if (!$config) {
			$config = new KConfig();
		}
		
		parent::__construct($config);

		if (is_null($config->secret)) 
		{
			$secret = KRequest::get('session.com.users.controller.behavior.spammable.secret', 'base64');
			
			if (!$secret) {
				// Create a random secret and store it in the session.
				$secret = sha1(time() . rand());
				KRequest::set('session.com.users.controller.behavior.spammable.secret', $secret);
			}
			
			$config->secret = $secret;
		}

		$this->_checks           = $config->checks;
		$this->_secret           = $config->secret;
		$this->_failed_checks    = array();
        $this->_json_spammed_msg = $config->json_spammed_msg;
	}
	
	protected function _initialize(KConfig $config)
	{
	    $config->append(array('json_spammed_msg' => 'Unable to process request', 'secret' => null, 'checks' => array()));
	    parent::_initialize($config);
	}

	protected function _beforeControllerAdd(KCommandContext $context)
	{
		if (!$this->whiteIp() && $this->isDispatched()) 
		{
			// Not whitelisted and dispatched (HMVC calls pass through), perform a spam check.
			if ($this->spammed(array('post' => $context->data))) 
			{
                // Set error on JSON requests.
                if (KRequest::get('get.format', 'cmd') == 'json') {
                    $context->setError(new KControllerBehaviorException(JText::_($this->_json_spammed_msg, KHttpResponse::INTERNAL_SERVER_ERROR)));
                }
                
                return false;
            }
		}
		
		return true;
	}
	
	protected function _afterControllerRead(KCommandContext $context)
	{
	    // Push the secret string to the view.
	    $this->getView()->assign('secret', $this->_secret);
	}

	/**
	 * Adds a client to the internal blacklist.
	 *
	 * @param	mixed The client data.
	 * @return 	mixed Null is already blacklisted, true if blacklisted, false otherwise.
	 */
	public function blacklist($data)
	{
		$result = null;

		$data = KConfig::unbox($data);
		
		$spammer = $this->getService('com://admin/users.database.row.spammer')
			            ->setData($data);
		$spammer->ip = $this->_getClientIp();
		
		// Update the internal blacklist (if necessary).
		if (!$spammer->load()) {
			$result = $spammer->save();
		}
		
		return $result;
	}

	/**
	 * Tells if the current client IP address is whitelisted (internal).
	 *
	 * @return boolean True if whitelisted, false otherwise.
	 */
	public function whiteIp()
	{
		if (!isset($this->_white_ip)) 
		{
			$this->_white_ip = false;
			$result = $this->getService('com://admin/users.database.row.whiteip')
			            ->setData(array('ip' => $this->_getClientIp()))
			            ->load();
			
			if ($result) {
				$this->_white_ip = true;
			}
		}
		
		return (bool)$this->_white_ip;
	}

	/**
	 * Tells if the current client IP address is blacklisted (internal).
	 *
	 * @return boolean True if whitelisted, false otherwise.
	 */
	public function blackIp()
	{
		if (!isset($this->_black_ip)) {
			$this->_black_ip = !(bool) $this->getService('com://admin/users.filter.spam.blacklist')->validate();
		}
		
		return $this->_black_ip;
	}

	/**
	 * Determines if a set of spam checks failed.
	 *
	 * @param	array 	The list of spam checks to be tested.
	 * @param	boolean If true, any single check in the list must fail in order for the method to return 
	 * 					true. If false, the method will return true if at least one check failed.
	 * @return boolean True if checks failed, false otherwise.
	 */
	public function spamChecksFailed($checks = array(), $strict = false)
	{
		$failed = array_intersect($checks, $this->_failed_checks);
		
		if (empty($failed)) {
			return false;
		}
		
		if ($strict) 
		{
			if (count($failed) != count($checks)) {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * Spammed status setter.
	 *
	 * @param	boolean The spammed status.
	 */
	public function setSpammed($status)
	{
	    $this->_spammed = (boolean)$status;
	}

	/**
	 * Failed spam checks getter.
	 *
	 * @return array A list of failed spam checks.
	 */
	public function getFailedSpamChecks()
	{
		return $this->_failed_checks;
	}
	
	/**
	 * Performs a spam check.
	 *
	 * @param	array An optional configuration array.
	 * @throws 	KControllerBehaviorException If a requested spam check is not implemented.
	 * @return boolean True if spam is suspected, false otherwise.
	 */
	public function spammed($data = array())
	{
		if (!isset($this->_spammed)) 
		{
			$data = new KConfig($data);

			// Initialize the spammed status as false.
			$this->_spammed = false;
			foreach ($this->_checks as $identifier => $config) 
			{
				if (is_numeric($identifier)) 
				{
					$identifier = $config;
					$config = array();
				} 
				else $config = KConfig::unbox($config);

				$filter = $this->getService($identifier, $config);

				if (!$filter instanceof ComUsersFilterSpamAbstract) {
					throw new KControllerBehaviorException('Bad filter');
				}

				if (!$filter->validate($this->_getData($data))) {
					$this->_failed_checks[] = $identifier;
				}
			}
			
			// Set current status as spammed.
			if (count($this->_failed_checks)) {
				$this->_spammed = true;
			}
		}

		return (bool)$this->_spammed;
	}

	/**
	 * Spam validator data getter.
	 *
	 * @param array|KConfig The data to be merged.
	 * @return KConfig The data to be used by spam validators.
	 */
	protected function _getData($data = array())
	{
		$data = new KConfig($data);

		$data->append(array('post' => array(),
						   'referrer' => KRequest::get('server.HTTP_REFERER', 'raw'),
						   'user_agent' => KRequest::get('server.HTTP_USER_AGENT', 'raw'),
						   'secret' => $this->_secret,
						   'ip' => $this->_getClientIp()))
			->append(array('reverse_ip' => $this->_reverseIp($data->ip)));

		return $data;
	}
	
	/**
	 * Client IP getter.
	 *
	 * @return The IP addresses from the client.
	 */
	protected function _getClientIp()
	{
		if ($this->_client_ip) {
			$this->_client_ip = KRequest::get('server . REMOTE_ADDR', 'raw');
		}
		
		return $this->_client_ip;
	}

	/**
	 * Returns a reversed IP address
	 *
	 * @param	string IP address.
	 * @return 	string Reversed IP address.
	 */
	protected function _reverseIp($ip)
	{
		return implode('.', array_reverse(explode('.', $ip)));
	}
}