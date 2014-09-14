<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Error Exception
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Exception\Error
 */
class ExceptionError extends \ErrorException implements Exception
{
    /**
     * Severity codes translation table.
     *
     * @link http://php.net/manual/en/errorfunc.constants.php
     *
     * @var array
     */
    public static $severity_messages = array(
        E_ERROR             => 'Fatal Error',
        E_USER_ERROR        => 'User Error',
        E_RECOVERABLE_ERROR => 'Recoverable Error',
        E_CORE_ERROR        => 'Core Error',
        E_COMPILE_ERROR     => 'Compile Error',
        E_PARSE             => 'Parse Error',
        E_WARNING           => 'Warning',
        E_CORE_WARNING      => 'Core Warning',
        E_COMPILE_WARNING   => 'Compile Warning',
        E_USER_WARNING      => 'User Warning',
        E_NOTICE            => 'Notice',
        E_USER_NOTICE       => 'User Notice',
        E_STRICT            => 'Strict standards',
        E_DEPRECATED        => 'Deprecated',
        E_USER_DEPRECATED   => 'User Deprecated'
    );

    /**
     * Return the severity message
     *
     * @return string
     */
    public function getSeverityMessage()
    {
        $severity = $this->getSeverity();

        if(isset(self::$severity_messages[$severity])) {
            $message = self::$severity_messages[$severity];
        } else {
            $message = 'Unknown error';
        }

        return $message;
    }
}