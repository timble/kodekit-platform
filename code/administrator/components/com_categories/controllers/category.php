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
 * Category Controller Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 */
class ComCategoriesControllerCategory extends ComDefaultControllerDefault
{
	public function loadState(KCommandContext $context)    
	{
        // Built the session identifier based on the action
        $identifier  = $this->getModel()->getIdentifier().'.'.$this->_action.'.'.$this->getModel()->get('section');
        $state       = KRequest::get('session.'.$identifier, 'raw', array());

        //Append the data to the request object
        $this->_request->append($state);

        //Push the request in the model
        $this->getModel()->set($this->getRequest());

        return $this;
     }

    public function saveState(KCommandContext $context)
    {
        $model  = $this->getModel();
        $state  = $model->get();

        // Built the session identifier based on the action
        $identifier  = $model->getIdentifier().'.'.$this->_action.'.'.$this->getModel()->get('section');
        
        //Set the state in the session
        KRequest::set('session.'.$identifier, $state);

        return $this;
     }
}
