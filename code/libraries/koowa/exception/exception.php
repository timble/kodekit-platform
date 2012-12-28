<?php
/**
 * @version		$Id$
 * @package		Koowa_Exception
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Exception Class
 *
 * KException is the base class for all related exceptions and provides an additional method for printing up a
 * detailed view of an exception.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Exception
 */
class KException extends \Exception implements KExceptionInterface
{
    /**
     * Constructor
     *
     * @param string  The exception message
     * @param integer The exception code
     * @param object  The previous exception
     */
    public function __construct($message = '', $code = KHttpResponse::INTERNAL_SERVER_ERROR, \Exception $previous = null)
    {
        parent::__construct($message, (int) $code, $previous);

        $traces = $this->getTrace();

        //Traverse up the trace stack to find the actual function that was not found
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