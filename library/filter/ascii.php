<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Ascii Filter
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Filter
 */
class FilterAscii extends FilterAbstract implements FilterTraversable
{
	/**
	 * Validate a variable
	 *
	 * Returns true if the string only contains US-ASCII
	 *
	 * @param	mixed	$value Value to be validated
	 * @return	bool	True when the variable is valid
	 */
    public function validate($value)
	{
		return (preg_match('/(?:[^\x00-\x7F])/', $value) !== 1);
	}

	/**
	 * Transliterate all unicode characters to US-ASCII. The string must be well-formed UTF8
	 *
     * @param   scalar  $value Value to be sanitized
	 * @return	scalar
	 */
    public function sanitize($value)
	{
		$string = htmlentities(utf8_decode($value));
		$string = preg_replace(
			array('/&szlig;/','/&(..)lig;/', '/&([aouAOU])uml;/','/&(.)[^;]*;/'),
			array('ss',"$1","$1".'e',"$1"),
			$string);

		return $string;
	}
}