<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Helper
 * @subpackage 	Math
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.koowa.org
 */

/**
 * Math methods
 *
 * @author      Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Helper
 * @subpackage 	Math
 * @static
 */
class KHelperMath
{
    /**
     * Round up value to a precision
     *
     * @param int
     * @param int Can be negative
     */
    public static function roundup ($value, $precision)
    {
        return ceil($value*pow(10, $precision))/pow(10, $precision);
    }

    /**
     * Round down value to a precision
     *
     * @param int
     * @param int Can be negative
     */
    public static function rounddown ($value, $dp)
    {
        return floor($value*pow(10, $dp))/pow(10, $dp);
    }

}