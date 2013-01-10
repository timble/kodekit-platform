<?php
/**
 * @version     $Id: exception.php 4629 2012-05-06 22:11:00Z johanjanssens $
 * @package     Koowa_Http
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Http Exception Bad Request Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Http
 */
class KHttpExceptionNotImplemented extends KException implements KHttpException
{
    /**
     * Constructor
     *
     * @param string  $message  The exception message
     * @param integer $code     The exception code
     * @param object  $previous The previous exception
     */
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        $code = KHttpResponse::NOT_IMPLEMENTED;

        if(!$message) {
            $message = KHttpResponse::$status_messages[$code];
        }

        parent::__construct($message, $code, $previous);
    }
}