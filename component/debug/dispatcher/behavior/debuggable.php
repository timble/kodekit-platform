<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Debug;

use Nooku\Library;

/**
 * Layoutable Dispatcher Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Application
 */
class DispatcherBehaviorDebuggable extends Library\DispatcherBehaviorAbstract
{
    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  Library\ObjectConfig $config A ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'priority'   => self::PRIORITY_LOW,
        ));

        parent::_initialize($config);
    }

    /**
     * Check if the behavior is supported
     *
     * @return  boolean  True on success, false otherwise
     */
    public function isSupported()
    {
        $mixer   = $this->getMixer();
        $request = $mixer->getRequest();

        if(in_array($request->getFormat(), array('json', 'html'))) {
            return true;
        }

        return false;
    }

    /**
     * Render the error and exception trace
     *
     * An exception is made for responses that include a Www-Authenicate header. In this case we assume that
     * the request is valid and the client should be giving the option to re-submit the request.
     *
     * @param 	Library\DispatcherContextInterface $context The active command context
     * @return 	void
     */
    protected function _beforeSend(Library\DispatcherContextInterface $context)
    {
        $response = $context->getResponse();
        $request  = $context->getRequest();

        //In case of an HTTP error render it and make an exception for WWw-Authenticate.
        if($response->isError() && !$response->headers->has('Www-Authenticate'))
        {
            //Check an exception was passed
            if(isset($context->exception) && $context->exception instanceof Library\Exception)
            {
                //Get the exception object
                $exception = $context->exception;

                $config = array(
                    //'request'  => $request,
                    'response' => $response
                );

                $this->getObject('com:debug.controller.error',  $config)
                    ->layout('default')
                    ->render($exception);
            }
        }
    }
}