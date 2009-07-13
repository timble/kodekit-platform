<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Session
 * @subpackage  Handler
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPL <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.koowa.org
 */

/**
 * Custom session storage handler for PHP
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_Session
 * @subpackage  Handler
 * @see http://www.php.net/manual/en/function.session-set-save-handler.php
 */
abstract class KSessionHandlerAbstract extends KObject
{
	/**
	 * Constructor
	 *
	 * @param array Optional parameters
	 * @throws KSessionHandlerException if the handler is not available
	 */
	public function __construct( array $options = array() )
	{
		if (!self::test()) 
		{
			$name = KInflector::getPart(get_class($filter), -1);
			throw new KSessionHandlerException("The ".$name." handler isn't available");
        }
		
		$this->_register();
	}

	/**
	 * Register the functions of this class with PHP's session handler
	 */
	protected function _register()
	{
		// use this object as the session handler
		session_set_save_handler(
			array($this, 'open'),
			array($this, 'close'),
			array($this, 'read'),
			array($this, 'write'),
			array($this, 'destroy'),
			array($this, 'gc')
		);
	}
}
