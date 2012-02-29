<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Activities
 * @copyright	Copyright (C) 2010 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Activities Model Class
 *
 * @author      Israel Canasa <http://nooku.assembla.com/profile/israelcanasa>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Activities
 */

class ComActivitiesModelActivities extends ComDefaultModelDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_state
			->insert('application' , 'cmd')
			->insert('type'        , 'cmd')
			->insert('package'     , 'cmd')
			->insert('name'        , 'cmd')
			->insert('action'      , 'cmd')
			->insert('user'        , 'cmd')
			->insert('distinct'    , 'boolean', false)
			->insert('column'      , 'cmd')
			->insert('start_date'  , 'date')
			->insert('days_back'   , 'int', 14)
			->insert('ip'		   , 'ip');

		$this->_state->remove('direction')->insert('direction', 'word', 'desc');

		// Force ordering by created_on
		$this->_state->sort = 'created_on';
	}

	protected function _buildQueryColumns(KDatabaseQuery $query)
	{
		if($this->_state->distinct && !empty($this->_state->column))
		{
			$query->distinct()
				->select($this->_state->column)
				->select($this->_state->column . ' AS activities_activity_id');
		}
		else
		{
			parent::_buildQueryColumns($query);
			$query->select('users.name AS created_by_name');
		}
	}

	protected function _buildQueryJoins(KDatabaseQuery $query)
	{
		$query->join('LEFT', 'users AS users', 'users.id = tbl.created_by');
	}

	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		parent::_buildQueryWhere($query);

		if ($this->_state->application) {
			$query->where('tbl.application', '=', $this->_state->application);
		}

		if ($this->_state->type) {
			$query->where('tbl.type', '=', $this->_state->type);
		}

		if ($this->_state->package && !($this->_state->distinct && !empty($this->_state->column))) {
			$query->where('tbl.package', '=', $this->_state->package);
		}

		if ($this->_state->name) {
			$query->where('tbl.name', '=', $this->_state->name);
		}

		if ($this->_state->action) {
			$query->where('tbl.action', 'IN', $this->_state->action);
		}

		if ($this->_state->start_date && $this->_state->start_date != '0000-00-00')
		{
			$start_date = $this->getService('koowa:date', array('date' => $this->_state->start_date));
			$days_back  = clone $start_date;
			$start      = $start_date->addDays(1)->addSeconds(-1)->getDate();

			$query->where('tbl.created_on', '<', $start);
			$query->where('tbl.created_on', '>', $days_back->addDays(-(int)$this->_state->days_back)->getDate());
		}

		if ($this->_state->user) {
			$query->where('tbl.created_by', '=', $this->_state->user);
		}
		
		if ($ip = $this->_state->ip) {
			$query->where('tbl.ip', 'IN', $ip); 
		}
	}

	protected function _buildQueryOrder(KDatabaseQuery $query)
	{
		if($this->_state->distinct && !empty($this->_state->column)) {
			$query->order('package', 'asc');
		} else {
		    parent::_buildQueryOrder($query);
		}
	}
}