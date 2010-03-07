<?php
/**
 * @version		$Id: users.php 214 2009-09-19 20:10:07Z johan $
 * @package		Profiles
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComProfilesTableUsers extends KDatabaseTableAbstract
{
	public function __construct(array $options = array())
	{
		$options['name'] = 'profiles_users';
		$options['base'] = 'users';
		
		parent::__construct($options);
	}
}