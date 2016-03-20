<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-deugger for the canonical source repository
 */

namespace Kodekit\Component\Debug;

use Kodekit\Library;

/**
 * Error Controller
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Debug
 */
class ControllerError extends Library\ControllerView
{
    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   Library\ObjectConfig $config Configuration options
     * @return void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'formats' => array('json'),
        ));

        parent::_initialize($config);
    }

    /**
     * Render an exception
     *
     * @throws \InvalidArgumentException If the action parameter is not an instance of Library\Exception
     * @param Library\ControllerContextInterface $context	A controller context object
     */
    protected function _actionRender(Library\ControllerContextInterface $context)
    {
        //Check an exception was passed
        if(!isset($context->param) && !$context->param instanceof Library\Exception)
        {
            throw new \InvalidArgumentException(
                "Action parameter 'exception' [Library\Exception] is required"
            );
        }

        //Set the exception data in the view
        $exception = $context->param;

        //If the error code does not correspond to a status message, use 500
        $code = $exception->getCode();
        if(!isset(Library\HttpResponse::$status_messages[$code])) {
            $code = '500';
        }

        $message = Library\HttpResponse::$status_messages[$code];

        //Get the exception back trace
        $traces = $this->getBackTrace($exception);

        //Cleanup the traces information
        foreach($traces as $key => $trace)
        {
            if(isset($trace['file'])) {
                $traces[$key]['file'] = str_replace(APPLICATION_ROOT, '', $trace['file']);
            }
        }

        //Traverse up the trace stack to find the actual function that was not found
        if(isset($traces[0]['function']) && $traces[0]['function'] == '__call')
        {
            foreach($traces as $trace)
            {
                if($trace['function'] != '__call')
                {
                    $message = "Call to undefined method : ".$trace['class'].$trace['type'].$trace['function'];
                    $file     = isset($trace['file']) ? $trace['file']  : '';
                    $line     = isset($trace['line']) ? $trace['line']  : '';
                    $function = $trace['function'];
                    $class    = $trace['class'];
                    $args     = isset($trace['args'])  ? $trace['args']  : '';
                    $info     = isset($trace['info'])  ? $trace['info']  : '';
                    break;
                }
            }
        }
        else
        {
            $message  = $exception->getMessage();
            $file	  = $exception->getFile();
            $line     = $exception->getLine();
            $function = isset($traces[0]['function']) ? $traces[0]['function'] : '';
            $class    = isset($traces[0]['class']) ? $traces[0]['class']       : '';
            $args     = isset($traces[0]['args'])  ? $traces[0]['args']        : '';
            $info     = isset($traces[0]['info'])  ? $traces[0]['info']        : '';
        }

        //Create the exception message
        if(ini_get('display_errors')) {
            $message = "Exception '".get_class($exception) ."' with message '".$message."' in ".$file.":".$line;
        } else {
            $traces = array();
        }

        $this->getView()->code     = $code;
        $this->getView()->message  = $message;
        $this->getView()->file     = $file;
        $this->getView()->line     = $line;
        $this->getView()->function = $function;
        $this->getView()->class    = $class;
        $this->getView()->args     = $args;
        $this->getView()->info     = $info;
        $this->getView()->trace    = $traces;
        $this->getView()->level    = $exception instanceof Library\ExceptionError ? $exception->getSeverityMessage() : false;

        //Render the exception
        $result = parent::_actionRender($context);

        return $result;
    }

    public function getBackTrace(\Exception $exception)
    {
        $traces = array();

        if($exception instanceof Library\ExceptionError)
        {
            $traces = $exception->getTrace();

            //Remove the first trace containing the call to KExceptionHandler
            unset($traces[0]);

            //Get trace from xdebug if enabled
            if($exception instanceof Library\ExceptionFailure && extension_loaded('xdebug') && xdebug_is_enabled())
            {
                $stack = array_reverse(xdebug_get_function_stack());
                $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

                $traces = array_diff_key($stack, $trace);
            }
        }
        else $traces = $exception->getTrace();

        //Remove the keys from the trace, we don't need those.
        $traces = array_values($traces);

        return $traces;
    }
}