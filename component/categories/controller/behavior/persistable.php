<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Categories;

use Nooku\Library;

/**
 * Persistable Controller Behavior
 *
 * @author  John Bell <http://nooku.assembla.com/profile/johnbell>
 * @package Nooku\Component\Categories
 */
class ControllerBehaviorPersistable extends Library\ControllerBehaviorPersistable
{ 
	/**
	 * Load the model state from the request
	 *
	 * This functions merges the request information with any model state information
	 * that was saved in the session and returns the result.
	 *
	 * @param 	Library\CommandContext		The active command context
	 * @return 	void
	 */
	protected function _beforeControllerBrowse(Library\CommandContext $context)
	{
		 // Built the session identifier based on the action
        $identifier  = $this->getModel()->getIdentifier().'.'.$this->_action.'.'.$this->getModel()->getState()->table;
        $state       = $context->user->get($identifier);

        //Add the data to the request query object
        $context->request->add($state);

        //Push the request query data in the model
        $this->getModel()->setState($context->request->query->toArray());
	}
	
	/**
	 * Saves the model state in the session
	 *
	 * @param 	Library\CommandContext		The active command context
	 * @return 	void
	 */
	protected function _afterControllerBrowse(Library\CommandContext $context)
	{
		$model = $this->getModel();
        $state = $model->getState();

        // Built the session identifier based on the action
        $identifier  = $model->getIdentifier().'.'.$this->_action.'.'.$this->getModel()->getState()->table;
        
        //Set the state in the user session
        $context->user->set($identifier, $state->getValues());
	}
}