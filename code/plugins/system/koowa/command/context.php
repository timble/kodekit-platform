<?php
/**
 * @version		$Id: interface.php 1366 2009-11-28 01:34:00Z johan $
 * @category	Koowa
 * @package		Koowa_Command
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Command Context
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Command
 */
class KCommandContext extends KConfig
{
	/**
	 * Error
	 *
	 * @var string
	 */
	protected $_error;
	
	/**
	 * Set the error
	 *
	 * @return	KCommandContext
	 */
	function setError($error) 
	{
		$this->_error = $error;
		return $this;
	}
	
	/**
	 * Get the error
	 *
	 * @return	string 	The error
	 */
	function getError() 
	{
		return $this->_error;
	}
}
