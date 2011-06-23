<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Persistable Controller Behavior Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
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
	protected function _beforeBrowse(KCommandContext $context)
	{
		 // Built the session identifier based on the action
        $identifier  = $this->getModel()->getIdentifier().'.'.$this->_action.'.'.$this->getModel()->get('section');
        $state       = KRequest::get('session.'.$identifier, 'raw', array());

        //Append the data to the request object
        $this->_request->append($state);

        //Push the request in the model
        $this->getModel()->set($this->getRequest());
	}
	
	/**
	 * Saves the model state in the session
	 *
	 * @param 	KCommandContext		The active command context
	 * @return 	void
	 */
	protected function _afterBrowse(KCommandContext $context)
	{
		$model  = $this->getModel();
        $state  = $model->get();

        // Built the session identifier based on the action
        $identifier  = $model->getIdentifier().'.'.$this->_action.'.'.$this->getModel()->get('section');
        
        //Set the state in the session
        KRequest::set('session.'.$identifier, $state);
	}
}