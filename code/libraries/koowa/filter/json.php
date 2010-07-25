<?php
/**
* @version		$Id$
* @category		Koowa
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.koowa.org
*/

/**
 * Integer filter
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
class KFilterJson extends KFilterAbstract
{
	/**
	 * Constructor
	 *
	 * @param 	object	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config) 
	{
		parent::__construct($config);
		
		//Don't walk the incoming data array or object
		$this->_walk = false;
	}
	
	/**
	 * Validate a value
	 *
	 * @param	scalar	Value to be validated
	 * @return	bool	True when the variable is valid
	 */
	protected function _validate($value)
	{
		return is_string($value) && !is_null(json_decode($value));
	}
	
	/**
	 * Sanitize a value
	 *
	 * @param	scalar	Value to be sanitized
	 * @return	string
	 */
	protected function _sanitize($value)
	{
		if(is_a($value, 'KConfig')) {
			$value = $value->toArray(); 
		}
		
		return json_encode($value);
	}
}

