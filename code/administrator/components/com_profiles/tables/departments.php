<?php
/**
 * @version		$Id: users.php 214 2009-09-19 20:10:07Z johan $
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComProfilesTableDepartments extends KDatabaseTableAbstract
{
	public function __construct(KConfig $config)
	{	
		$config->name = 'profiles_view_departments';
		$config->base = 'profiles_departments';
	
		parent::__construct($config);
	}
	
	public function filter($data, $base = false)
	{
		settype($data, 'array'); //force to array
		
		if(isset($data['title']) && empty($data['alias'])) {
			$data['alias'] = $data['title'];
		}
		
		return parent::filter($data, $base);
	}
}