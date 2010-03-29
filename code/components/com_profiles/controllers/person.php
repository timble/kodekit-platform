<?php
/** 
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * Person Controller
 *
 * @package		Profiles
 */
class ComProfilesControllerPerson extends ComDefaultControllerView
{
	/**
	 * Load the model state
	 * 
	 * Force load enabled items onlye
	 *
	 * @return void
	 */
	public function getRequest()
	{
		$state = array_merge(parent::getRequest(), array('enabled' => 1));
		return $state;
	}
	
	/**
	 * Display a single item
	 *
	 * @return KDatabaseRow	A row object containing the selected row
	 */
	protected function _actionRead()
	{		
		$row = parent::_actionRead();
		
		if(KRequest::get('get.layout', 'cmd') != 'form' && $row->isHittable()) {
			$row->hit();
		}
			
		return $row;
	}
}