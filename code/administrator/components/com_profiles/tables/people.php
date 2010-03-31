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
	public function __construct(KConfig $config)
	{
		$config->name = 'profiles_view_people';
		$config->base = 'profiles_people';
		
		parent::__construct($config);
	}
	
	public function filter($data)
	{
		settype($data, 'array'); //force to array
		
		if(empty($data['alias'])) {
			$data['alias'] = strtolower($data['firstname'].'_'.$data['lastname']);
		}
	
		return parent::filter($data);
	}
}