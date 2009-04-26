<?php
/**
* @version 		$Id:koowa.php 251 2008-06-14 10:06:53Z mjaz $
* @category 	Koowa
* @package 		Koowa_Filter
* @copyright 	Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
* @license 		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link 		http://www.koowa.org
*/
 
/**
* Slug filter
*
* A slug is an integer id followed by a colon and a string, eg 15:my_title
*
* @author Mathias Verraes <mathias@joomlatools.org>
* @category Koowa
* @package Koowa_Filter
*/
class KFilterSlug extends KFilterAbstract
{
	/**
	 * Validate a variable
	 *
	 * @param mixed Variable to be validated
	 * @return bool True when the variable is valid
	*/
	protected function _validate($var)
	{
		if(empty($var)) {
			return true;
		}
		
		$int = KFactory::tmp('lib.koowa.filter.int');
		$cmd = KFactory::tmp('lib.koowa.filter.cmd');
		$parts = explode(':', $var, 2);
		switch(count($parts))
		{
			case 1:
				return $int->validate($parts[0]);
				break;
			case 2:
				return $int->validate($parts[0]) && $cmd->validate($parts[1]);
				break;
		}
	}

	/**
	 * Sanitize a variable
	 *
	 * @param mixed Variable to be sanitized
	 * @return int
	 */
	protected function _sanitize($var)
	{
		if(empty($var)) {
			return 0;
		}

		$int = KFactory::tmp('lib.koowa.filter.int');
		$cmd = KFactory::tmp('lib.koowa.filter.cmd');
		$parts = explode(':', $var, 2);

		switch(count($parts))
		{
			case 1:
				return $int->sanitize($parts[0]);
				break;

			case 2:
				return $int->sanitize($parts[0]) .':'. $cmd->sanitize($parts[1]);
				break;
		}
	}
}