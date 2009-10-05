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

Koowa::import('admin::com.beer.models.view');

class BeerModelUsers extends BeerModelView
{
	public function __construct(array $options = array())
	{
		parent::__construct($options);
		
		// Set the state
		$this->_state->insert('gid', 'int');
	}
	
	/*public function getTable(array $options = array())
	{
		$options['primary'] = 'id';
		
		return parent::getTable($options);
	}*/
	
	public function getGroups()
	{
		$query = $this->_db->getQuery()
			->select(array('gid', 'usertype'))
			->from('beer_viewusers AS tbl')
			->group('gid')
			->order('gid');
		
		$result = $this->_db->fetchObjectList($query);
		return $result; 
	}
	
	public function getView(array $options = array())
	{
		if(!($this->_view instanceof KDatabaseTableAbstract || is_null($this->_view))) 
		{
			$name = $this->_identifier->name;
			
			$options['table_name'] = 'beer_view'.$name;
			$options['primary']    = 'id';
			$options['database']   = $this->_db;
			
			try	{
				$this->_view = KFactory::get($this->_view, $options);
			} catch ( KDatabaseTableException $e ) { 
				$this->_view = null;
			}
		}

		return $this->_view;
	}
	
	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$state = $this->_state;
		
		if($state->search) {
			$query->where('tbl.name', 'LIKE',  '%'.$state->search.'%');
		}
		
		if ($state->gid) {
			$query->where('tbl.gid','=', $state->gid);
		}
	}
	
	/*public function getUsers($selected = null)
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
	}*/
}