<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Search Default Template Class
 *
 * @author    	Arunas Mazeika <http://nooku.assembla.com/profile/amazeika>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Search
 */
class ComSearchTemplateDefault extends KTemplateDefault
{
	/**
	 * Load a template helper
	 * 
	 * This function merges the elements of the attached view model state with the parameters passed to the helper
	 * so that the values of one are appended to the end of the previous one. 
	 * 
	 * If the view state have the same string keys, then the parameter value for that key will overwrite the state.
	 *
	 * @param   string  Name of the helper, dot separated including the helper function to call
	 * @param   mixed   Parameters to be passed to the helper
	 * @return  string  Helper output
	 */
	public function loadHelper($identifier, $params = array())
	{
		
		if($state = $this->getView()->getModel()->getState()) {
			$params = array_merge($state->getData(), $params);
		}
		
		return parent::loadHelper($identifier, $params);
	}
}