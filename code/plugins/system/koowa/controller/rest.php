<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Controller
 * @copyright	Copyright (C) 2007 Joomlatools. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * REST Controller Class
 *
 * @author		Mathias Verraes <mathias@joomlatools.eu> 
 * @category	Koowa
 * @package     Koowa_Controller
 */
class KControllerRest extends KControllerAbstract
{	
	public function __construct()
	{
		parent::__construct();
		
		// needed because get() is already in KObject
		$this->registerAction('get', 'get');
	}
	
	/**
	 * Get the action that is was/will be performed.
	 *
	 * @return	 string Action name
	 */
	public function getAction()
	{
		if(!isset($this->_action))
		{
			// Find the action from the _method variable, or use the request method
    		$post_method	= strtolower(KInput::get('post._method', 'cmd'));
    		
    		if(is_null($post_method)) { // no _method provided
    			$this->_action = strtolower(KInput::getMethod());
    		} else {
    			if(in_array($post_method, array('get', 'post', 'delete', 'put'))) {
    				throw new KControllerException('Unknown _method type: '.$post_method);
    			}
    			$this->_action = $post_method;
    		}
		}
		return $this->_action;
	}
	
	/**
	 * Typical REST read action
	 */
	public function get()	
	{
		return KInflector::isPlural($view) ? $this->browse() : $this->read();
	}
	
	public function post()
	{
		// if there are no id's, we are adding an item
		return (!$id && !count($cid)) ? parent::add() : parent::edit();
	}
	
	public function put()
	{
		return parent::add();
	}
	
	public function delete()
	{
		return parent::delete();
	}
	
}
