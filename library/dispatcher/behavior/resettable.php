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
 * Resettable Dispatcher Behavior - Post, Redirect, Get
 *
 * When a user sends a POST request (e.g. after submitting a form), their browser will try to protect them from sending
 * the POST again, breaking the back button, causing browser warnings and pop-ups, and sometimes re-posting the form.
 *
 * Instead, when receiving a POST and when we are explicitly asking the browser to reset the form we should redirect the
 * user through a GET request to prevent duplicate form submissions.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherBehaviorResettable extends ControllerBehaviorAbstract
{
    /**
     * Get an object handle
     *
     * Only attach this behavior for form (application/x-www-form-urlencoded) POST requests.
     *
     * @return string A string that is unique, or NULL
     * @see execute()
     */
    public function getHandle()
    {
        $result = null;
        if($this->getRequest()->isPost() && $this->getRequest()->getContentType() == 'application/x-www-form-urlencoded') {
            $result = parent::getHandle();
        }

        return $result;
    }

    /**
	 * Force a GET after POST using the referrer
     *
     * Method will only set the redirect for none AJAX requests and only if the controller has a returned a 2xx status
     * code. In all other cases no redirect will be set.
	 *
	 * @param DispatcherContextInterface $context	A dispatcher context object
	 * @return 	void
	 */
	protected function _beforeSend(DispatcherContext $context)
	{
        if(!$context->request->isAjax() && $context->response->getStatusCode() == HttpResponse::RESET_CONTENT) {
            $context->response->setRedirect($context->request->getReferrer());
        }
	}
}