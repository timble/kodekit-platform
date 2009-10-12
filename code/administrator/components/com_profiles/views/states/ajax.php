<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ProfilesViewStates extends KViewAjax
{
	public function display($tpl = 'ajax')
	{
		$this->assign('region', KRequest::get( 'get.region', 'string' ));
		$this->assign('format', 'ajax');
		parent::display($tpl);
	}

}
