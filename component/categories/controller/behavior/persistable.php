<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Categories;

use Nooku\Library;

/**
 * Persistable Controller Behavior
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
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
	 * @param 	Library\ControllerContextInterface $context A controller context object
	 * @return 	void
	 */
	protected function _beforeBrowse(Library\ControllerContextInterface $context)
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
	 * @param 	Library\ControllerContextInterface $context A controller context object
	 * @return 	void
	 */
	protected function _afterBrowse(Library\ControllerContextInterface $context)
	{
		$model = $this->getModel();
        $state = $model->getState();

        // Built the session identifier based on the action
        $identifier  = $model->getIdentifier().'.'.$this->_action.'.'.$this->getModel()->getState()->table;
        
        //Set the state in the user session
        $context->user->set($identifier, $state->getValues());
	}
}