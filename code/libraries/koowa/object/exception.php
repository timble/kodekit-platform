<?php
/**
 * @version     $Id:exception.php 368 2008-08-25 12:28:02Z mathias $
 * @category	Koowa
 * @package     Koowa_Object
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Koowa Date Exception class
 *
 * @author      Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Object
 */
class KObjectException extends KException 
{
  	/**
	 * Constructor
	 *
	 * @param string  The exception message
	 * @param integer The exception code
	 * @param object  The previous exception
	 */
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        
        //Get the call stack
        $traces = $this->getTrace();

        //Traverse up the trace stack to find the actuall function that was not found
        if($traces[0]['function'] == '__call') 
        {
        	foreach($traces as $trace)
        	{
        		if($trace['function'] != '__call')
        		{
        			$this->message = "Call to undefined method : ".$trace['class'].$trace['type'].$trace['function'];
     				$this->file    = $trace['file'];
     				$this->line    = $trace['line'];
        			break;
        		}
        	}
        }
    }
}

    