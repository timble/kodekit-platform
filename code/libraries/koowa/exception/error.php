<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Exception
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Koowa Error Exception Class
 *
 * KException is the base class for all koowa related exceptions and
 * provides an additional method for printing up a detailed view of an
 * exception.
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Exception
 */
class KExceptionError extends ErrorException implements KExceptionInterface
{
    /**
	 * Constructor
	 *
	 * @param string  The exception message
	 * @param integer The exception code
	 */
    public function __construct($message, $code, $severity, $filename, $lineno)
    {
    	if (!$message) {
            throw new $this('Unknown '. get_class($this));
        }

        parent::__construct($message, $code, $severity, $filename, $lineno);
    }

 	/**
	 * Format the exception for display
	 *
	 * @return string
	 */
    public function __toString()
    {
		 return "exception '".get_class($this) ."' with message '".$this->getMessage()."' in ".$this->getFile().":".$this->getLine()
		 		."\nStack trace:\n"
		 		. "  " . str_replace("\n", "\n  ", $this->getTraceAsString());
    }
}