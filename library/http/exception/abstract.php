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
 * Abstract Http Exception
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Http
 */
abstract class HttpExceptionAbstract extends \RuntimeException implements HttpException
{
    /**
     * Constructor
     *
     * @param string  $message  The exception message
     * @param object  $previous The previous exception
     */
    public function __construct($message = null, \Exception $previous = null)
    {
        parent::__construct($message, $this->code, $previous);
    }
}