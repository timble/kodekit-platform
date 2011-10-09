<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Plugins Module Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions    
 */
class ComExtensionsModelPlugins extends ComDefaultModelDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
	
		$this->_state
		 	->insert('sort'   , 'cmd', 'folder')
		 	->insert('enabled', 'boolean')
		 	->insert('type'   , 'cmd')
		 	->insert('hidden' , 'boolean');	
	}

	protected function _buildQueryJoin(KDatabaseQuery $query)
	{
		$query->join('left', 'groups AS group', 'group.id = tbl.access');

		parent::_buildQueryJoin($query);
	}

	protected function _buildQueryWhere(KDatabaseQuery $query)
	{
		$state = $this->_state;

		if($state->search) {
			$query->where('tbl.name', 'LIKE', '%'.$state->search.'%');
		}
		
		if($state->type) {
			$query->where('tbl.folder', '=', $state->type);
		}

		if(is_bool($state->enabled)) {
			$query->where('tbl.published', '=', (int) $state->enabled);
		}
		
	    if(is_bool($state->hidden)) {
			$query->where('tbl.iscore', '=', (int) $state->hidden);
		}

		parent::_buildQueryWhere($query);
	}
}