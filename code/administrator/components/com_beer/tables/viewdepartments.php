<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * 
 * @version		$Id$
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * We hardly use our tables directly (and we do, Nooku Framework renders the table, 
 * row and rowset objects automatically). But Nooku Framework doesn't really support 
 * database views yet, so we need this little hack. Views don't have primary keys, 
 * so we fake them
 */
class BeerTableViewdepartments extends KDatabaseTableAbstract
{
	public function getPrimaryKey()
	{
		return 'beer_department_id';
	}
}