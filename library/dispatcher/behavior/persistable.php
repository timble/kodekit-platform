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
	 * @param 	CommandContext $context The active command context
	 * @return 	void
	 */
	protected function _beforeControllerBrowse(CommandContext $context)
	{
		$model = $this->getController()->getModel();

		// Built the session identifier based on the action
		$identifier  = $model->getIdentifier().'.'.$context->action;
		$state       = $context->user->session->get($identifier, array());

		//Append the data to the request object
		$context->request->query->add($state);

		//Push the request in the model
		$model->set($context->request->query->toArray());
	}

	/**
	 * Saves the model state in the session.
	 *
	 * @param 	CommandContext $context The active command context
	 * @return 	void
	 */
	protected function _afterControllerBrowse(CommandContext $context)
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
		$identifier = $model->getIdentifier().'.'.$context->action;

        //Prevent unused state information from being persisted
        $context->user->session->remove($identifier);

        //Set the state in the session
        $context->user->session->set($identifier, $vars);
	}
}