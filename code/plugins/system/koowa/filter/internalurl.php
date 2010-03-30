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
 * Internal url filter
 * 
 * Check if an refers to a legal URL inside the system. Use when 
 * redirecting to an URL that was passed in a request
 *
 * @todo		Do a proper implementation, see NookuFilterEditlink for ideas
 * 
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
class KFilterInternalurl extends KFilterAbstract
{
	/**
	 * Validate a value
	 *
	 * @param	scalar	Value to be validated
	 * @return	bool	True when the variable is valid
	 */
	protected function _validate($value)
	{
		if(!is_string($value)) {
			return false;
		}
				
		if(stripos($value, (string)  dirname(KRequest::url()->get(KHttpUri::PART_BASE))) !== 0) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Sanitize a value
	 *
	 * @param	scalar	Value to be sanitized
	 * @return	string
	 */
	protected function _sanitize($value)
	{
		//TODO : internal url's should not only have path and query information
		return filter_var($value, FILTER_SANITIZE_URL);
	}
}

