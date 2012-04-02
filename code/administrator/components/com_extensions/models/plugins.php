<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
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
	
		$this->getState()
		 	->insert('sort'   , 'cmd', 'folder')
		 	->insert('enabled', 'boolean')
		 	->insert('type'   , 'cmd')
		 	->insert('hidden' , 'boolean');	
	}

	protected function _buildQueryJoin(KDatabaseQuerySelect $query)
	{
		$query->join(array('groups' => 'groups'), 'groups.id = tbl.access');

		parent::_buildQueryJoin($query);
	}

	protected function _buildQueryWhere(KDatabaseQuerySelect $query)
	{
	    parent::_buildQueryWhere($query);
		$state = $this->getState();

		if($state->search) {
			$query->where('tbl.name LIKE :search')->bind(array('search' => '%'.$state->search.'%'));
		}
		
		if($state->type) {
			$query->where('tbl.folder = :type')->bind(array('type' => $state->type));
		}

		if(is_bool($state->enabled)) {
			$query->where('tbl.published = :enabled')->bind(array('enabled' => (int) $state->enabled));
		}
		
	    if(is_bool($state->hidden)) {
			$query->where('tbl.iscore = :hidden')->bind(array('hidden' => (int) $state->hidden));
		}
	}
}