<?php
/**
 * @version		$Id$
 * @package		Koowa_Dispatcher
 * @subpackage  Router
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract Router Class
 *
 * Provides route building and parsing functionality
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage  Router
 */
abstract class KDispatcherRouterAbstract extends KObject implements KDispatcherRouterInterface
{
	/**
	 * An array of variables
	 *
	 * @var array
	 */
	protected $_vars = array();

	/**
	 * An array of rules
	 *
	 * @var array
	 */
	protected $_rules = array(
		'build' => array(),
		'parse' => array()
	);

	/**
	 * Function to convert a route to an internal URI
     *
     * @param   JURI  $uri  The uri.
     * @return  array
	 */
	public function parse($uri)
	{
		$vars = array();

		// Process the parsed variables based on custom defined rules
		$vars = $this->_processParseRules($uri);

		// Parse SEF URL
		$vars += $vars + $this->_parseRoute($uri);

	 	return  array_merge($this->getVars(), $vars);
	}

	/**
	 * Function to convert an internal URI to a route
	 *
	 * @param	string	$string	The internal URL
	 * @return	string	The absolute search engine friendly URL
	 */
	public function build($url)
	{
		//Create the URI object
		$uri = $this->_createUrl($url);

		//Process the uri information based on custom defined rules
		$this->_processBuildRules($uri);

		// Build SEF URL : mysite/route/index.php?var=x
		$this->_buildRoute($uri);

		return $uri;
	}

	/**
	 * Set a router variable, creating it if it doesn't exist
	 *
	 * @param	string  $key    The name of the variable
	 * @param	mixed   $value  The value of the variable
	 * @param	boolean $create If True, the variable will be created if it doesn't exist yet
     * @return \KDispatcherRouterInterface
 	 */
	public function setVar($key, $value, $create = true)
    {
        if ($create || array_key_exists($key, $this->_vars)) {
			$this->_vars[$key] = $value;
		}

        return $this;
	}

	/**
	 * Set the router variable array
	 *
	 * @param	array   $vars   An associative array with variables
	 * @param	boolean $create If True, the array will be merged instead of overwritten
     * @return \KDispatcherRouterInterface
 	 */
	public function setVars($vars = array(), $merge = true)
    {
		if($merge) {
			$this->_vars = array_merge($this->_vars, $vars);
		} else {
			$this->_vars = $vars;
		}

        return $this;
	}

	/**
	 * Get a router variable
	 *
	 * @param	string $key   The name of the variable
	 * @return  mixed  Value of the variable
 	 */
	public function getVar($key)
	{
		$result = null;
		if(isset($this->_vars[$key])) {
			$result = $this->_vars[$key];
		}

		return $result;
	}

	/**
	 * Get the router variable array
	 *
	 * @return  array An associative array of router variables
 	 */
	public function getVars()
    {
		return $this->_vars;
	}

	/**
	 * Attach a build rule
	 *
	 * @param   callback $callback The function to be called.
 	 */
	public function attachBuildRule($callback)
	{
		$this->_rules['build'][] = $callback;
	}

	/**
	 * Attach a parse rule
	 *
	 * @param   callback $callback The function to be called.
 	 */
	public function attachParseRule($callback)
	{
		$this->_rules['parse'][] = $callback;
	}

	/**
	 * Process the parsed router variables based on custom defined rules
     *
     * @param   JURI  $uri  The URI to parse
     * @return  array  The array of processed URI variables
	 */
	protected function _processParseRules($uri)
	{
		$vars = array();

		foreach($this->_rules['parse'] as $rule) {
			$vars = call_user_func_array($rule, array(&$this, &$uri));
		}

		return $vars;
	}

	/**
	 * Process the build uri query data based on custom defined rules
     *
     * @param   JURI  $uri  The URI
     * @return  void
	 */
	protected function _processBuildRules($uri)
	{
		foreach($this->_rules['build'] as $rule) {
			call_user_func_array($rule, array(&$this, &$uri));
		}
	}

	/**
	 * Create a uri based on a full or partial url string
     *
     * @param   string  $url  The URI
     * @return  JURI
 	 */
	protected function _createUrl($url)
	{
		// Decompose link into url component parts
		$uri = new JURI($url);
		return $uri;
	}

	/**
	 * Encode route segments
     *
     * @param   array  $segments  An array of route segments
     * @return  array  Array of encoded route segments
 	 */
	protected function _encodeSegments($segments)
	{
		$total = count($segments);
		for($i=0; $i<$total; $i++) {
			$segments[$i] = str_replace(':', '-', $segments[$i]);
		}

		return $segments;
	}

	/**
	 * Decode route segments
     *
     * @param   array  $segments  An array of route segments
     * @return  array  Array of decoded route segments
 	 */
	protected function _decodeSegments($segments)
	{
		$total = count($segments);
		for($i=0; $i<$total; $i++)  {
			$segments[$i] = preg_replace('/-/', ':', $segments[$i], 1);
		}

		return $segments;
	}
}
