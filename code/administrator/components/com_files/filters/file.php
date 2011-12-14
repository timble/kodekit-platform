<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Filter class for validating file names
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */
class ComFilesFilterFile extends KFilterAbstract
{
    protected static $_pattern = array('#(\.){2,}#', '#^\.#');

    protected static $_special_chars = array(
        "?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", "'", "\"", 
        "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}"
    );

    /**
     * Validate a value
     *
     * @param	scalar	Value to be validated
     * @return	bool	True when the variable is valid
     */
    protected function _validate($value)
    {
        $sanitized = $this->sanitize($value);
        return (is_string($value) && $sanitized == $value);
    }

    /**
     * Sanitize a value
     *
     * @param	mixed	Value to be sanitized
     * @return	string
     */
    protected function _sanitize($value)
    {
		$value = preg_replace(self::$_pattern, '', $value);
        $value = str_replace(self::$_special_chars, '', $value);
        $value = str_replace('/', '', $value);

        return $value;
    }
}
