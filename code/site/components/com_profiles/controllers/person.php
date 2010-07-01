<?php
/** 
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 - 2010 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * Person Controller
 *
 * @package		Profiles
 */
class ComProfilesControllerPerson extends ComDefaultControllerDefault
{
 	protected function _initialize(KConfig $config)
    {
    	//Force to show only show enabled items
    	$config->request->enabled = 1;
    	
        parent::_initialize($config);
    }
	
	protected function _actionRead()
	{		
		$row = parent::_actionRead();
		
		if($this->_request->layout != 'form' && $row->isHittable()) {
			$row->hit();
		}
			
		return $row;
	}
}