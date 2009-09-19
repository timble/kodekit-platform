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
	public function getUsers($selected = null)
	{
		$db = KFactory::get('lib.koowa.database');
		$query = $db->getQuery()
				->select(array('u.id AS id', 'u.name AS name'))
        		->from('users AS u')
				->join('LEFT OUTER', 'beer_people AS p', 'p.user_id = u.id')
				->where('p.user_id', 'IS NULL')
				->order('name')	;	

		if ($selected) {
			$query->where('u.id', '=', $selected, 'OR');
		}

		return $db->fetchObjectList($query);			 
	}

}