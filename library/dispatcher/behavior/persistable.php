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
 * Persistable Dispatcher Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Dispatcher
 */
class DispatcherBehaviorPersistable extends ControllerBehaviorAbstract
{
    /**
     * Get an object handle
     *
     * Disable dispatcher persistency on non-HTTP requests, e.g. AJAX. This avoids changing the model state session
     * variable of the requested model, which is often undesirable under these circumstances.
     *
     * @return string A string that is unique, or NULL
     * @see execute()
     */
    public function getHandle()
    {
        $result = null;
        if($this->getRequest()->isGet() && !$this->getRequest()->isAjax()) {
            $result = parent::getHandle();
        }

        return $result;
    }

    /**
	 * Load the model state from the request and persist it.
	 *
	 * This functions merges the request information with any model state information that was saved in the session
     * and returns the result.
	 *
	 * @param DispatcherContextInterface $context	A dispatcher context object
	 * @return 	void
	 */
	protected function _beforeGet(DispatcherContextInterface $context)
	{
        if($this->getController() instanceof ControllerModellable)
        {
            $model = $this->getController()->getModel();

            // Built the session identifier based on the action
            $identifier  = $model->getIdentifier();
            $state       = $context->user->getSession()->get($identifier, array());

            //Append the data to the request object
            $context->request->query->add($state);

            //Push the request in the model
            $model->getState()->setValues($context->request->query->toArray());
        }
	}

	/**
	 * Saves the model state in the session.
	 *
	 * @param DispatcherContextInterface $context	A dispatcher context object
	 * @return 	void
	 */
	protected function _afterGet(DispatcherContextInterface $context)
	{
        if($this->getController() instanceof ControllerModellable)
        {
            $model  = $this->getController()->getModel();
            $state  = $model->getState();

            $vars = array();
            foreach($state->toArray() as $var)
            {
                if(!$var->unique) {
                    $vars[$var->name] = $var->value;
                }
            }

            // Built the session identifier based on the action
            $identifier = $model->getIdentifier();

            //Prevent unused state information from being persisted
            $context->user->getSession()->remove($identifier);

            //Set the state in the session
            $context->user->getSession()->set($identifier, $vars);
        }
	}
}