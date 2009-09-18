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

class BeerModelUsers extends BeerModelView
{
	public function getUsers()
	{
		$db = KFactory::get('lib.koowa.database');
		$query = $db->getQuery()
				->select(array('id', 'name'))
        		->from('users')
				->order('name')	;	
		return $db->fetchObjectList($query);			 
	}

}