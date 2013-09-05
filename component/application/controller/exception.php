<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Exception Controller
 *   
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Application
 */
class ControllerException extends Library\ControllerView
{
    /**
     * Render an exception
     *
     * @throws \InvalidArgumentException If the action parameter is not an instance of Library\Exception
     * @param Library\CommandContext $context	A command context object
     */
    protected function _actionRender(Library\CommandContext $context)
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
        $traces = $exception->getTrace();

        //Find the real file path
        $aliases = $this->getObject('manager')->getClassLoader()->getAliases();

        //Cleanup the traces information
        foreach($traces as $key => $trace)
        {
            if(isset($trace['file']))
            {
                if($alias = array_search($trace['file'], $aliases)) {
                    $trace['file'] = $alias;
                };

                $traces[$key]['file'] = str_replace(JPATH_ROOT, '', $trace['file']);
            }
        }

        //Traverse up the trace stack to find the actual function that was not found
        if($traces[0]['function'] == '__call')
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
            $file	  = isset($traces[0]['file']) ? $traces[0]['file'] : $exception->getFile();
            $line     = $exception->getLine();
            $function = $traces[0]['function'];
            $class    = isset($traces[0]['class']) ? $traces[0]['class'] : '';
            $args     = isset($traces[0]['args'])  ? $traces[0]['args']  : '';
            $info     = isset($traces[0]['info'])  ? $traces[0]['info']  : '';
        }

        //Find and use file alias if it exists
        if($alias = array_search($file, $aliases)) {
            $file = str_replace(JPATH_ROOT, '', $alias);;
        };

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

        //Make sure the buffers are cleared
        while(@ob_get_clean());

        //Render the exception
        $result = parent::_actionRender($context);

        //Set the response status
        $context->response->setStatus($code , $message);

        return $result;
    }
}