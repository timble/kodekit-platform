<?php

class ComFilesFilterPath extends KFilterAbstract
{
    protected static $pattern = '#^[a-z0-9_\.-\s/:~]*$#i';

     protected static $safepath_pattern = array('#(\.){2,}#', '#^\.#');

    /**
     * Validate a value
     *
     * @param	scalar	Value to be validated
     * @return	bool	True when the variable is valid
     */
    protected function _validate($value)
    {
        $value = trim(str_replace('\\', '/', $value));
        return (is_string($value) && (preg_match(self::$pattern, $value)) == 1);
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
        preg_match(self::$pattern, $value, $matches);
        $match = isset($matches[0]) ? $matches[0] : '';

		return preg_replace(self::$safepath_pattern, '', $match);
    }
}
