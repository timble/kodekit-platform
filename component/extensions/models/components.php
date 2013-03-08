<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

use Nooku\Framework;

/**
 * Components Model
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Extensions
 */
class ComExtensionsModelComponents extends ComDefaultModelDefault
{
	public function __construct(Framework\Config $config)
	{
		parent::__construct($config);
	
		$this->getState()
		 	->insert('enabled', 'boolean')
		 	->insert('name', 'cmd')
            ->insert('sort', 'tbl.name');
	}
	
	protected function _buildQueryWhere(Framework\DatabaseQuerySelect $query)
	{
	    parent::_buildQueryWhere($query);
		$state = $this->getState();
	
		if($state->search) {
			$query->where('tbl.name LIKE :search')->bind(array('search' => '%'.$state->search.'%'));
		}
		
		if($state->name) {
			$query->where('tbl.name = :name')->bind(array('name' => $state->name));
		}

		if(is_bool($state->enabled)) {
			$query->where('tbl.enabled = :enabled')->bind(array('enabled' => (int) $state->enabled));
		}
	}
}