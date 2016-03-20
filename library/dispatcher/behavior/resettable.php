<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Resettable Dispatcher Behavior - Post, Redirect, Get
 *
 * When a browser sends a POST request (e.g. after submitting a form), the browser will try to protect them from sending
 * the POST again, breaking the back button, causing browser warnings and pop-ups, and sometimes re-posting the form.
 *
 * Instead, when receiving a none AJAX POST request reset the browser by redirecting it through a GET request.
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Dispatcher
 */
class DispatcherBehaviorResettable extends DispatcherBehaviorAbstract
{
    /**
     * Check if the behavior is supported
     *
     * @return  boolean  True on success, false otherwise
     */
    public function isSupported()
    {
        $mixer   = $this->getMixer();
        $request = $mixer->getRequest();

        if(!$request->isSafe() && !$request->isAjax() && $request->getFormat() == 'html') {
            return true;
        }

        return false;
    }

    /**
     * Force a GET after POST using the referrer
     *
     * Redirect if the controller has a returned a 2xx status code.
     *
     * @param 	DispatcherContextInterface $context The active command context
     * @return 	void
     */
    protected function _beforeSend(DispatcherContextInterface $context)
    {
        $response = $context->response;
        $request  = $context->request;

        if($response->isSuccess()) {
            $response->setRedirect($request->getReferrer());
        }
    }
}