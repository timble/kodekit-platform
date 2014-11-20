<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * String
 *
 * Helper class for utf-8 data. All functions assume the validity of utf-8 strings.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\String
 * @static
 */
interface StringInterface
{
    /**
     * UTF-8 aware alternative to strpos
     *
     * Find position of first occurrence of a string
     *
     * @param $str - string String being examined
     * @param $search - string String being searced for
     * @param $offset - int Optional, specifies the position from which the search should be performed
     * @return mixed Number of characters before the first match or FALSE on failure
     * @see http://www.php.net/strpos
     */
    public static function strpos($str, $search, $offset = FALSE);

    /**
     * UTF-8 aware alternative to strrpos
     *
     * Finds position of last occurrence of a string
     *
     * @param $str - string String being examined
     * @param $search - string String being searced for
     * @return mixed Number of characters before the last match or FALSE on failure
     * @see http://www.php.net/strrpos
     */
    public static function strrpos($str, $search);

    /**
     * UTF-8 aware alternative to substr
     *
     * Return part of a string given character offset (and optionally length)
     *
     * @param string
     * @param integer number of UTF-8 characters offset (from left)
     * @param integer (optional) length in UTF-8 characters from offset
     * @return mixed string or FALSE if failure
     * @see http://www.php.net/substr
     */
    public static function substr($str, $offset, $length = FALSE);

    /**
     * UTF-8 aware alternative to strtlower
     *
     * Make a string lowercase
     *
     * Note: The concept of a characters "case" only exists is some alphabets
     * such as Latin, Greek, Cyrillic, Armenian and archaic Georgian - it does
     * not exist in the Chinese alphabet, for example. See Unicode Standard
     * Annex #21: Case Mappings
     *
     * @param string
     * @return mixed either string in lowercase or FALSE is UTF-8 invalid
     * @see http://www.php.net/strtolower
     */
    public static function strtolower($str);

    /**
     * UTF-8 aware alternative to strtoupper
     *
     * Make a string uppercase
     *
     * Note: The concept of a characters "case" only exists is some alphabets
     * such as Latin, Greek, Cyrillic, Armenian and archaic Georgian - it does
     * not exist in the Chinese alphabet, for example. See Unicode Standard
     * Annex #21: Case Mappings
     *
     * @param string
     * @return mixed either string in uppercase or FALSE is UTF-8 invalid
     * @see http://www.php.net/strtoupper
     */
    public static function strtoupper($str);

    /**
     * UTF-8 aware alternative to strlen
     *
     * Returns the number of characters in the string (NOT THE NUMBER OF BYTES),
     *
     * @param string UTF-8 string
     * @return int number of UTF-8 characters in string
     * @see http://www.php.net/strlen
     */
    public static function strlen($str);

    /**
     * UTF-8 aware alternative to str_ireplace
     *
     * Case-insensitive version of str_replace
     *
     * @param string string to search
     * @param string existing string to replace
     * @param string new string to replace with
     * @param int optional count value to be passed by referene
     * @see http://www.php.net/str_ireplace
    */
    public static function str_ireplace($search, $replace, $str, $count = NULL);

    /**
     * UTF-8 aware alternative to str_split
     *
     * Convert a string to an array
     *
     * @param string UTF-8 encoded
     * @param int number to characters to split string by
     * @return array
     * @see http://www.php.net/str_split
    */
    public static function str_split($str, $split_len = 1);

    /**
     * UTF-8 aware alternative to strcasecmp
     *
     * A case insensivite string comparison
     *
     * @param string string 1 to compare
     * @param string string 2 to compare
     * @return int < 0 if str1 is less than str2; > 0 if str1 is greater than str2, and 0 if they are equal.
     * @see http://www.php.net/strcasecmp
    */
    public static function strcasecmp($str1, $str2);

    /**
     * UTF-8 aware alternative to strcspn
     * Find length of initial segment not matching mask
     *
     * @param string
     * @param string the mask
     * @param int Optional starting character position (in characters)
     * @param int Optional length
     * @return int the length of the initial segment of str1 which does not contain any of the characters in str2
     * @see http://www.php.net/strcspn
    */
    public static function strcspn($str, $mask, $start = NULL, $length = NULL);

    /**
     * UTF-8 aware alternative to stristr
     *
     * Returns all of haystack from the first occurrence of needle to the end.
     * needle and haystack are examined in a case-insensitive manner
     * Find first occurrence of a string using case insensitive comparison
     *
     * @param string the haystack
     * @param string the needle
     * @return string the sub string
     * @see http://www.php.net/stristr
    */
    public static function stristr($str, $search);

    /**
     * UTF-8 aware alternative to strrev
     *
     * Reverse a string
     *
     * @param string String to be reversed
     * @return string The string in reverse character order
     * @see http://www.php.net/strrev
     */
    public static function strrev($str);

    /**
     * UTF-8 aware alternative to strspn
     *
     * Find length of initial segment matching mask
     *
     * @param string the haystack
     * @param string the mask
     * @param int start optional
     * @param int length optional
     * @see http://www.php.net/strspn
    */
    public static function strspn($str, $mask, $start = NULL, $length = NULL);

    /**
     * UTF-8 aware substr_replace
     *
     * Replace text within a portion of a string
     *
     * @param string the haystack
     * @param string the replacement string
     * @param int start
     * @param int length (optional)
     * @see http://www.php.net/substr_replace
    */
    public static function substr_replace($str, $repl, $start, $length = NULL );

    /**
     * UTF-8 aware replacement for ltrim()
     *
     * Strip whitespace (or other characters) from the beginning of a string
     * Note: you only need to use this if you are supplying the charlist
     * optional arg and it contains UTF-8 characters. Otherwise ltrim will
     * work normally on a UTF-8 string
     *
     * @param string the string to be trimmed
     * @param string the optional charlist of additional characters to trim
     * @return string the trimmed string
     * @see http://www.php.net/ltrim
    */
    public static function ltrim( $str, $charlist = FALSE );

    /**
     * UTF-8 aware replacement for rtrim()
     *
     * Strip whitespace (or other characters) from the end of a string
     * Note: you only need to use this if you are supplying the charlist
     * optional arg and it contains UTF-8 characters. Otherwise rtrim will
     * work normally on a UTF-8 string
     *
     * @param string the string to be trimmed
     * @param string the optional charlist of additional characters to trim
     * @return string the trimmed string
     * @see http://www.php.net/rtrim
    */
    public static function rtrim( $str, $charlist = FALSE );

    /**
     * UTF-8 aware replacement for trim()
     *
     * Strip whitespace (or other characters) from the beginning and end of a string
     * Note: you only need to use this if you are supplying the charlist
     * optional arg and it contains UTF-8 characters. Otherwise trim will
     * work normally on a UTF-8 string
     *
     * @param string the string to be trimmed
     * @param string the optional charlist of additional characters to trim
     * @return string the trimmed string
     * @see http://www.php.net/trim
    */
    public static function trim( $str, $charlist = FALSE );

    /**
     * UTF-8 aware alternative to ucfirst
     *
     * Make a string's first character uppercase
     *
     * @param string
     * @return string with first character as upper case (if applicable)
     * @see http://www.php.net/ucfirst
    */
    public static function ucfirst($str);

    /**
     * UTF-8 aware alternative to ucwords
     *
     * Uppercase the first character of each word in a string
     *
     * @param string
     * @return string with first char of each word uppercase
     * @see http://www.php.net/ucwords
    */
    public static function ucwords($str);

    /**
     * Callback function for preg_replace_callback call in utf8_ucwords
     *
     * You don't need to call this yourself
     *
     * @param array of matches corresponding to a single word
     * @return string with first char of the word in uppercase
     * @see ucwords
     * @see strtoupper
     */
    public static function ucwords_callback($matches);

    /**
     * Transcode a string.
     *
     * @param string $source The string to transcode.
     * @param string $from_encoding The source encoding.
     * @param string $to_encoding The target encoding.
     * @return string Transcoded string
     */
    public static function transcode($source, $from_encoding, $to_encoding);

    /**
     * Tests a string as to whether it's valid UTF-8 and supported by the Unicode standard
     *
     * Note: this function has been modified to simple return true or false
     *
     * @author <hsivonen@iki.fi>
     * @param string UTF-8 encoded string
     * @return boolean true if valid
     * @see http://hsivonen.iki.fi/php-utf8/
     * @see compliant
     */
    public static function valid($str);

    /**
     * Tests whether a string complies as UTF-8. This will be much
     * faster than utf8_is_valid but will pass five and six octet
     * UTF-8 sequences, which are not supported by Unicode and
     * so cannot be displayed correctly in a browser. In other words
     * it is not as strict as utf8_is_valid but it's faster. If you use
     * is to validate user input, you place yourself at the risk that
     * attackers will be able to inject 5 and 6 byte sequences (which
     * may or may not be a significant risk, depending on what you are
     * are doing)
     *
     * @see valid
     * @see http://www.php.net/manual/en/reference.pcre.pattern.modifiers.php#54805
     * @param string UTF-8 string to check
     * @return boolean TRUE if string is valid UTF-8
     */
    public static function compliant($str);
}