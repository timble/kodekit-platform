<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 - 2010 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComProfilesControllerDashboard extends ComDefaultControllerDefault
{
	protected function _initialize(KConfig $config)
    {
    	$config->append(array(
    		'request' => array('layout' => 'default'),
        ));

        parent::_initialize($config);
    }
	
	public function displayView(KCommandContext $context)
	{
		KRequest::set('get.hidemainmenu', 0);	
		return parent::displayView($context);
	}
}