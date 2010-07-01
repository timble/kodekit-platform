<?php
/**
 * @version		$Id: users.php 214 2009-09-19 20:10:07Z johan $
 * @package		Profiles
 * @copyright	Copyright (C) 2009 - 2010 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComProfilesTablePeople extends KDatabaseTableAbstract
{
	protected function _initialize(KConfig $config)
    {
    	//Create a custom sluggable behavior
    	$sluggable = KFactory::get('lib.koowa.database.behavior.sluggable',
    		 array('columns' => array('id', 'firstname', 'lastname'))	
    	);
    	
    	$config->behaviors = array('hittable', 'lockable', 'creatable', 'modifiable', $sluggable);
		
		$config->name = 'profiles_view_people';
		$config->base = 'profiles_people';
    
		parent::_initialize($config);
    }
}