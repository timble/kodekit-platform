<?php
/**
 * @version     $Id: dispatcher.php 4948 2012-09-03 23:05:48Z johanjanssens $
 * @package     Koowa_Event
 * @subpackage 	Dispatcher
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Error Event Dispatcher Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Event
 * @subpackage 	Dispatcher
 */
class KEventDispatcherException extends KEventDispatcherAbstract
{
    /**
     * The error level.
     *
     * @var int
     */
    protected $_error_level;

    /**
     * Constructor.
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->setErrorLevel($config->error_level);

        if($config->catch_user_errors) {
            $this->registerErrorHandler();
        }

        if($config->catch_core_errors) {
            //$this->registerShutdownHandler();
        }

        if($config->catch_exceptions) {
            $this->registerExceptionHandler();
        }
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options.
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'catch_exceptions'  => true,
            'catch_user_errors' => true,
            'catch_core_errors' => true,
            'error_level'       => null,
        ));

        parent::_initialize($config);
    }

    /**
     * Set the error level
     *
     * @param int $level If NULL, will reset the level to the system default.
     */
    public function setErrorLevel($level)
    {
        $this->_error_level = null === $level ? error_reporting() : $level;
    }

    /**
     * Get the error level
     *
     * @return int The error level
     */
    public function getErrorLevel($level)
    {
        return $this->_error_level;
    }

    /**
     * Register the exception handler.
     *
     * @return  Returns the name of the previously defined exception handler, or NULL if no previous handler
     *          was defined.
     */
    public function registerExceptionHandler()
    {
        $self = $this; //Cannot use $this as a lexical variable in PHP 5.3
        $previous = set_exception_handler(function($exception) use ($self) {
            $self->dispatchException('onException', array('exception' => $exception));
        });

        return $previous;
    }

    /**
     * Register the error handler.
     *
     * @return  Returns the name of the previously defined error handler, or NULL if no previous handler
     *          was defined.
     */
    public function registerErrorHandler()
    {
        $error_level = $this->_error_level;
        $self        = $this; //Cannot use $this as a lexical variable in PHP 5.3
        $previous = set_error_handler(function($level, $message, $file, $line, $context) use ($self, $error_level)
        {
            if (0 === $error_level) {
                return false;
            }

            if (error_reporting() & $level && $error_level & $level)
            {
                $exception = new KExceptionError($message, 500, $level, $file, $line);
                $self->dispatchException('onException', array(
                    'exception' => $exception,
                    'context'   => $context
                ));
            }

            //Let the normal error flow continue
            return false;
        });

        return $previous;
    }

    /**
     * Register a shutdown handler.
     *
     * @return  void
     */
    public function registerShutdownHandler()
    {
        $error_level = $this->_error_level;
        $self        = $this; //Cannot use $this as a lexical variable in PHP 5.3

        register_shutdown_function(function() use ($self, $error_level)
        {
            $error = error_get_last();
            $level = $error['type'];

            if (error_reporting() & $level && $error_level & $level)
            {
                $exception = new KExceptionError($error['message'], 500, $level, $error['file'], $error['line']);
                $self->dispatchException('onException', array('exception' => $exception));
            }
        });
    }

    /**
     * Dispatches an exception by dispatching arguments to all listeners that handle the event.
     *
     * Function will avoid a recursive loop when an exception is thrown during even dispatching
     * and output a generic exception instead.
     *
     * @link    http://www.php.net/manual/en/function.set-exception-handler.php#88082
     * @param   string  The event name
     * @param   object|array   An array, a KConfig or a KEvent object
     * @return  KEventException
     */
    public function dispatchException($name, $event = array())
    {
        try
        {
            //Make sure we have an event object
            if (!$event instanceof KEventException) {
                $event = new KEventException($event);
            }

            parent::dispatchEvent($name, $event);
        }
        catch (Exception $e)
        {
            $message = "<strong>Exception</strong> '%s' thrown while dispatching error: %s in <strong>%s</strong> on line <strong>%s</strong> %s";
            $message = sprintf($message, get_class($e), $e->getMessage(), $e->getFile(), $e->getLine(), $e->getTraceAsString());

            if (ini_get('display_errors')) {
                echo $message;
            }

            if (ini_get('log_errors')) {
                error_log($message);
            }

            exit(0);
        }

        return $event;
    }
}