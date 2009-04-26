<?php
/**
* @version      $Id:koowa.php 251 2008-06-14 10:06:53Z mjaz $
* @category		Koowa
* @package      Koowa_Filter
* @copyright    Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.koowa.org
*/

/**
 * Abstract filter.
 *
 * @author		Johan Janssens <johan@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
abstract class KFilterAbstract extends KObject implements KFilterInterface
{
	/**
	 * Validate the data
	 *
	 * @param	mixed	Data to be validated
	 * @return	bool	True when the data is valid
	 */
	public final function validate($data)
	{
		if(is_array($data) || is_object($data)) 
		{
			$arr = (array)$data;
			
			foreach($arr as $value) 
			{
				if($this->validate($value) ===  false) {
					return false;
				}
			}
		} 
		else 
		{	
			if($this->_validate($data) ===  false) {
				return false;
			}
		}
			
		return true;
	}
	
	/**
	 * Sanitize the data
	 *
	 * @param	mixed	Data to be sanitized
	 * @return	mixed	The sanitized data
	 */
	public final function sanitize($data)
	{
		if(is_array($data) || is_object($data)) 
		{
			$arr = (array)$data;
			
			foreach($arr as $key => $value) 
			{
				if(is_array($data)) {
					$data[$key] = $this->sanitize($value);
				}
				
				if(is_object($data)) {
					$data->$key = $this->sanitize($value);	
				}
			}
		}
		else $data = $this->_sanitize($data);
		
		return $data;
	}


	/**
	 * Validate a variable
	 *
	 * @param	mixed	Variable to be validated
	 * @return	bool	True when the variable is valid
	 */
	abstract protected function _validate($var);
	
	/**
	 * Sanitize a variable
	 *
	 * @param	mixed	Variable to be sanitized
	 * @return	mixed
	 */
	abstract protected function _sanitize($var);
}