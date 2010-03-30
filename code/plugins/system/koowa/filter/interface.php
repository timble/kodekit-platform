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
 * Filter interface
 *
 * Validate or sanitize data
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
interface KFilterInterface extends KCommandInterface
{
	/**
	 * Validate a value or data collection
	 *
	 * NOTE: This should always be a simple yes/no question (is $value valid?), so 
	 * only true or false should be returned
	 * 
	 * @param	mixed	Data to be validated
	 * @return	bool	True when the variable is valid
	 */
	public function validate($value);
	
	/**
	 * Sanitize a value or data collection
	 *
	 * @param	mixed	Data to be sanitized
	 * @return	mixed
	 */
	public function sanitize($value);
}