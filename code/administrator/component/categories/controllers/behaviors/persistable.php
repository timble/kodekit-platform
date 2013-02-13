<?php
/**
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Persistable Controller Behavior Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @package     Nooku_Server
 * @subpackage  Categories   
 */
class ComCategoriesControllerBehaviorPersistable extends KControllerBehaviorPersistable
{ 
	/**
	 * Load the model state from the request
	 *
	 * This functions merges the request information with any model state information
	 * that was saved in the session and returns the result.
	 *
	 * @param 	KCommandContext		The active command context
	 * @return 	void
	 */
	protected function _beforeControllerBrowse(KCommandContext $context)
	{
		 // Built the session identifier based on the action
        $identifier  = $this->getModel()->getIdentifier().'.'.$this->_action.'.'.$this->getModel()->get('table');
        $state       = $context->user->get($identifier);

        //Add the data to the request query object
        $context->request->add($state);

        //Push the request query data in the model
        $this->getModel()->set($context->request->query->toArray());
	}
	
	/**
	 * Saves the model state in the session
	 *
	 * @param 	KCommandContext		The active command context
	 * @return 	void
	 */
	protected function _afterControllerBrowse(KCommandContext $context)
	{
		$model  = $this->getModel();
        $state  = $model->getState();

        // Built the session identifier based on the action
        $identifier  = $model->getIdentifier().'.'.$this->_action.'.'.$this->getModel()->get('table');
        
        //Set the state in the user session
        $context->user->set($identifier, $state->toArray());
	}
}