<?php
/**
 * @version		$Id$
 * @package		Profiles
 * @copyright	Copyright (C) 2009 - 2010 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComProfilesModelDepartments extends ComProfilesModelGroups
{
	public function __construct(KConfig $config)
	{
		$config->table_behaviors = array('lockable', 'creatable', 'modifiable');
		
		parent::__construct($config);
	}
}