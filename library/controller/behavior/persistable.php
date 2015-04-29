<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Persistable Controller Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Controller
 */
class ControllerBehaviorPersistable extends ControllerBehaviorAbstract
{
    /**
     * Check if the behavior is supported
     *
     * Disable controller persistency on non-HTTP requests, e.g. AJAX. This avoids changing the model state session
     * variable of the requested model, which is often undesirable under these circumstances.
     *
     * @return  boolean  True on success, false otherwise
     */
    public function isSupported()
    {
        $mixer   = $this->getMixer();
        $request = $mixer->getRequest();

        if ($mixer instanceof ControllerModellable && $mixer->isDispatched() && $request->isGet() && $request->getFormat() === 'html') {
            return true;
        }

        return false;
    }

    /**
     * Returns a key based on the context to persist state values
     *
     * @param 	ControllerContextInterface $context The active controller context
     * @return  string
     */
    protected function _getStateKey(ControllerContextInterface $context)
    {
        $view   = $this->getView()->getIdentifier();
        $layout = $this->getView()->getLayout();
        $model  = $this->getModel()->getIdentifier();

        return $view.'.'.$layout.'.'.$model.'.'.$context->action;
    }

    /**
	 * Load the model state from the request and persist it.
	 *
	 * This functions merges the request information with any model state information that was saved in the session
     * and returns the result.
	 *
	 * @param ControllerContextInterface $context	A controller context object
	 * @return 	void
	 */
	protected function _beforeBrowse(ControllerContextInterface $context)
	{
        $query = $context->getRequest()->query;

        $query->add((array) $context->user->get($this->_getStateKey($context)));

        $this->getModel()->getState()->setValues($query->toArray());
	}

	/**
	 * Saves the model state in the session.
	 *
	 * @param ControllerContextInterface $context	A controller context object
	 * @return 	void
	 */
	protected function _afterBrowse(ControllerContextInterface $context)
	{
        $state  = $this->getModel()->getState();

        $vars = array();
        foreach($state->toArray() as $var)
        {
            if(!$var->unique && !$var->internal) {
                $vars[$var->name] = $var->value;
            }
        }

        $context->user->set($this->_getStateKey($context), $vars);
	}
}