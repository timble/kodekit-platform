<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * 
 * @version		$Id: users.php 214 2009-09-19 20:10:07Z johan $
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class BeerTableUsers extends KDatabaseTableAbstract
{
	public function __construct(array $options = array())
	{
		$options['table_name']	= 'users';
		$options['primary']		= 'id';
		
		parent::__construct($options);
	}
}