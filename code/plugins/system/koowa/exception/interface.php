<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Exception
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.koowa.org
 */

/**
 * Exception Interface
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Exception
 */
interface KExceptionInterface
{
	/**
	 * Return the exception message
	 *
	 * @return string
	 */
    public function getMessage();      

    /**
	 * Return the user defined exception code
	 *
	 * @return integer
	 */
    public function getCode(); 

    /**
	 * Return the source filename
	 *
	 * @return string
	 */
    public function getFile();                    
    
    /**
	 * Return the source line number
	 *
	 * @return integer
	 */
    public function getLine();                    
    
    /**
	 * Return the backtrace information
	 *
	 * @return array
	 */
    public function getTrace();                  
    
    /**
	 * Return the backtrace as a string
	 *
	 * @return string
	 */
    public function getTraceAsString();           
   
    /**
	 * Format the exception for display
	 *
	 * @return string
	 */
    public function __toString();                 
    
    /**
	 * Constructor
	 *
	 * @parem string  The exception message
	 * @param integer The exception code
	 */
    public function __construct($message = null, $code = 0);
}