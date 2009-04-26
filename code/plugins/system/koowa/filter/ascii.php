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
 * Ascii filter
 *
 * @author		Mathias Verraes <mathias@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Filter
 */
class KFilterAscii extends KFilterAbstract
{
	/**
	 * Options for the filter
	 *
	 * @var	array
	 */
	protected $_default_char = '?';
	
	/**
	 * Produce an URL safe string
	 *
	 * @var	array
	 */
	protected $_url_safe = true;
	
	/**
	 * Transliteration data from ascii/data/*
	 *
	 * @var	array
	 */
	protected static $_data = array();
	
	/**
	 * Location of the data files
	 *
	 * @var	string
	 */
	protected $_data_dir;
	
	/**
	 * Constructor
	 *
	 * @param	array	Options array
	 */
	public function __construct(array $options = array())
	{
		if(isset($options['default_char'])) {
			$this->_default_char = $options['default_char'];
		}
		
		if(isset($options['url_safe'])) {
			$this->_url_safe = $options['url_safe'];
		}
		
		$this->_data_dir = dirname(__FILE__).DS.'ascii'.DS.'data';
	}
	
	/**
	 * Set default character when no replacement is found
	 *
	 * @param	string	Character
	 * @return	this
	 */
	public function setDefaultChar($char)
	{
		$this->_default_char = $char;
		return $this;
	}
	
	/**
	 * Validate a variable
	 * 
	 * Returns true if the string only contains US-ASCII
	 *
	 * @param	mixed	Variable to be validated
	 * @return	bool	True when the variable is valid
	 */
	protected function _validate($var)
	{
		return (preg_match('/(?:[^\x00-\x7F])/', $var) !== 1);
	}
	
	/**
	 * Transliterate all unicode characters to US-ASCII. The string must be 
	 * well-formed UTF8
	 * 
	 * This is a fork from utf8-to-ascii by Harry Fuecks, which was in turn a 
	 * port from Sean M. Burke's Text::Unidecode Perl module
	 * 
	 * For European languages, it should replace Unicode character with 
	 * corresponding ascii characters and produce a readable result. For other 
	 * languages, the results will be less meaningful - it's a "dumb" character 
	 * for character replacement True trasliteration is a little more complex 
	 * than this; See: http://en.wikipedia.org/wiki/Transliteration
	 * 
	 * For any characters for which there's no replacement character available, 
	 * a (default) '?' will be inserted. A different replacement string can be 
	 * defined in the constructor's options array or using setDefaultChar()
	 * 
	 * @link 	http://sourceforge.net/projects/phputf8/
	 * @link 	http://www.sitepoint.com/blogs/2006/03/03/us-ascii-transliterations-of-unicode-text/
	 * @link 	http://phputf8.sourceforge.net/#UTF_8_Validation_and_Cleaning
	 *
	 * @param	scalar	Variable to be sanitized
	 * @throws KFilterException
	 * @return	scalar
	 */
	protected function _sanitize($var)
	{
		$len = strlen($var);
	    if ( $len == 0 ) { 
	    	return ''; 
	    }
	    $i = 0;

	    /*
	     * Use an output buffer to copy the transliterated string. This is done 
	     * for performance vs. string concatenation.
	     * @see http://phplens.com/lens/php-book/optimizing-debugging-php.php
	     * Section  "High Return Code Optimizations"
	     */
	    ob_start();
	    
	    while ( $i < $len ) {
	        
	        $ord = NULL;
	        $increment = 1;
	        
	        $ord0 = ord($var{$i});
	        
	        // Much nested if /else - PHP fn calls expensive, no block scope...
	        
	        // 1 byte - ASCII
	        if ( $ord0 >= 0 && $ord0 <= 127 ) {
	            
	            $ord = $ord0;
	            $increment = 1;
	            
	        } else {
	            
	            // 2 bytes
	            $ord1 = ord($var{$i+1});
	            
	            if ( $ord0 >= 192 && $ord0 <= 223 ) {
	                
	                $ord = ( $ord0 - 192 ) * 64 + ( $ord1 - 128 );
	                $increment = 2;
	                
	            } else {
	                
	                // 3 bytes
	                $ord2 = ord($var{$i+2});
	                
	                if ( $ord0 >= 224 && $ord0 <= 239 ) {
	                    
	                    $ord = ($ord0-224)*4096 + ($ord1-128)*64 + ($ord2-128);
	                    $increment = 3;
	                    
	                } else {
	                    
	                    // 4 bytes
	                    $ord3 = ord($var{$i+3});
	                    
	                    if ($ord0>=240 && $ord0<=247) {
	                        
	                        $ord = ($ord0-240)*262144 + ($ord1-128)*4096 
	                            + ($ord2-128)*64 + ($ord3-128);
	                        $increment = 4;
	                        
	                    } else {
	                        ob_end_clean();
	                        throw new KFilterException("Badly formed UTF-8 at byte $i");
	                    }
	                    
	                }
	                
	            }
	            
	        }

	        $bank = $ord >> 8;
	        
	        // If we haven't used anything from this bank before, need to load it...
	        if ( !array_key_exists($bank, self::$_data )) {
	            
	            $bankfile = $this->_data_dir. DS. sprintf("x%02x",$bank).'.php';
	            if ( file_exists($bankfile) ) {
	                
	                // Load the appropriate database
	                if ( !include  $bankfile ) {
	                    ob_end_clean();
	                    throw new KFilterException("Unable to load $bankfile");
	                }
	                
	            } else {
	                
	                // Some banks are deliberately empty
	                self::$_data[$bank] = array();
	                
	            }
	        }
	        
	        $newchar = $ord & 255;

	        if ( array_key_exists($newchar, self::$_data[$bank]) ) {
	            echo self::$_data[$bank][$newchar];
	        } else {
	            echo $this->_default_char;
	        }
	        
	        $i += $increment;
	        
	    }
	 
	    $result = ob_get_clean(); 
	    
	    //Convert the string to an URL safe string
	    if($this->_url_safe)
	    {
			// remove any duplicate whitespace, and ensure all characters are alphanumeric
			$result = preg_replace(array('/\s+/','/[^A-Za-z0-9\-]/'), array('-',''), $result);

			// lowercase and trim
			$result = trim(strtolower($result));
	    }
		
		return $result;
	}
}