<?php
/**
* @version		$Id$
* @category		Nooku
* @package    	Nooku_Server
* @subpackage  	Editors
* @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
* @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link			http://www.nooku.org
*/

/**
 * Editor Controller Class
 *
 * @author    	Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Editor
 */
class ComEditorsControllerEditor extends KControllerResource
{
	public function __call($method, $args)
	{
	    //Check first if we are calling a mixed in method. 
	    //This prevents the model being loaded durig object instantiation. 
		if(!isset($this->_mixed_methods[$method]) && $method != 'display') 
        {
            //Check if the method is a state property
			$view = $this->getView();
			$view->$method($args[0]);
	
			return $this;
        }
		
		return parent::__call($method, $args);
	}
}