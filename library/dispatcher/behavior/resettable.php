<?php
/**
 * @package		Koowa_Dispatcher
 * @subpackage	Behavior
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Resettable Dispatcher Behavior Class
 *
 * When a user sends a POST request (e.g. after submitting a form), their browser will try to protect them from sending
 * the POST again, breaking the back button, causing browser warnings and pop-ups, and sometimes reposting the form.
 * Instead, when receiving a POST we should redirect the user to a GET request.
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Dispatcher
 * @subpackage	Behavior
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
	 * @param 	CommandContext $context The active command context
	 * @return 	void
	 */
	protected function _afterControllerDispatch(CommandContext $context)
	{
        if(!$context->request->isAjax() && $context->response->isSuccess()) {
            $context->response->setRedirect($context->request->getReferrer());
        }
	}
}