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
 * Path Filter Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesFilterPath extends KFilterAbstract
{
    protected static $_safepath_pattern = array('#(\.){2,}#', '#^\.#');

    protected static $_special_chars = array(
        "?", "[", "]", "\\", "=", "<", ">", ":", ";", "'", "\"", 
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
        $value = trim(str_replace('\\', '/', $value));
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
        $value = trim(str_replace('\\', '/', $value));
        $value = preg_replace(self::$_safepath_pattern, '', $value);
        $value = str_replace(self::$_special_chars, '', $value);

        // TODO: allow utf-8
        $value = preg_replace('#[^a-zA-Z0-9_ \/\.\_\- ]#', '', $value);

		return $value;
    }
}
