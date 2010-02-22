<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Exception
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Koowa Exception Class
 *
 * KException is the base class for all koowa related exceptions and
 * provides an additional method for printing up a detailed view of an
 * exception.
 * 
 * KException has support for nested exceptions which is a feature that
 * was only added in PHP 5.3
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Exception
 */
class KException extends Exception implements KExceptionInterface
{
    /**
     * Previous exception if nested exception
     *
     * @var Exception
     */ 
	protected $previous;
    
    /**
	 * Constructor
	 *
	 * @param string  The exception message
	 * @param integer The exception code
	 * @param object  The previous exception
	 */
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
    	if (!$message) {
            throw new $this('Unknown '. get_class($this));
        }

        parent::__construct($message, $code);
        
    	if (!is_null($previous)) {
            $this->previous = $previous;
        }
    }
    
    /**
	 * Get the previous Exception
	 *
	 * @return Exception
	 */
	public function getPrevious()
    {
    	return $this->previous;
   	}

 	/**
	 * Format the exception for display
	 *
	 * @return string
	 */
    public function __toString()
    {
		 return "Exception '".get_class($this) ."' with message '".$this->getMessage()."' in ".$this->getFile().":".$this->getLine();
    }
}