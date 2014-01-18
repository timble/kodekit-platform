<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Exception Event Publisher
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Event
 */
class EventPublisherException extends EventPublisherAbstract
{
    /**
     * Error levels
     */
    const ERROR_SYSTEM       = null;
    const ERROR_DEVELOPMENT  = -1; //E_ALL   | E_STRICT  | ~E_DEPRECATED
    const ERROR_PRODUCTION   = 7;  //E_ERROR | E_WARNING | E_PARSE

    /**
     * The error level.
     *
     * @var int
     */
    protected $_error_level;

    /**
     * Constructor.
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the error level
        $this->setErrorLevel($config->error_level);

        if($config->catch_user_errors) {
            $this->catchUserErrors();
        }

        if($config->catch_fatal_errors) {
            $this->catchFatalErrors();
        }

        if($config->catch_exceptions) {
            $this->catchExceptions();
        }
    }

    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'catch_exceptions'   => true,
            'catch_user_errors'  => true,
            'catch_fatal_errors' => true,
            'error_level'        => self::ERROR_SYSTEM,
        ));

        parent::_initialize($config);
    }

    /**
     * Publish an event by calling all listeners that have registered to receive it.
     *
     * Function will avoid a recursive loop when an exception is thrown during even publishing and output a generic
     * exception instead.
     *
     * @param  \Exception           $exception  The exception to be published.
     * @param  array|\Traversable   $attributes An associative array or a Traversable object
     * @param  mixed                $target     The event target
     * @return EventException
     */
    public function publishException(Exception $exception, $attributes = array(), $target = null)
    {
        try
        {
            //Make sure we have an event object
            $event = new EventException('onException', $attributes, $target);
            $event->setException($exception);

            parent::publishEvent($event);
        }
        catch (\Exception $e)
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
    public function getErrorLevel()
    {
        return $this->_error_level;
    }

    /**
     * Catch exceptions during runtime
     *
     * @return  string|null Returns the name of the previously defined exception handler, or NULL if no previous handler
     *                      was defined.
     */
    public function catchExceptions()
    {
        $self = $this; //Cannot use $this as a lexical variable in PHP 5.3

        $previous = set_exception_handler(function($exception) use ($self) {
            $self->publishException($exception);
        });

        return $previous;
    }

    /**
     * Catch user errors during runtime
     *
     * @return  string|null Returns the name of the previously defined error handler, or NULL if no previous handler
     *                      was defined.
     */
    public function catchUserErrors()
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
                $exception = new ExceptionError($message, HttpResponse::INTERNAL_SERVER_ERROR, $level, $file, $line);
                $self->publishException($exception, array('context' => $context));
            }

            //Let the normal error flow continue
            return false;
        });

        return $previous;
    }

    /**
     * Catch fatal errors after shutdown.
     *
     * @return  void
     */
    public function catchFatalErrors()
    {
        $error_level = $this->_error_level;
        $self        = $this; //Cannot use $this as a lexical variable in PHP 5.3

        register_shutdown_function(function() use ($self, $error_level)
        {
            $error = error_get_last();
            $level = $error['type'];

            if (error_reporting() & $level && $error_level & $level)
            {
                $exception = new ExceptionError($error['message'], HttpResponse::INTERNAL_SERVER_ERROR , $level, $error['file'], $error['line']);
                $self->publishException($exception);
            }
        });
    }
}