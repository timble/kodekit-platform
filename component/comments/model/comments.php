<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Comments;

use Nooku\Library;

/**
 * Comments Model
 *
 * @author  Steven Rombauts <https://nooku.assembla.com/profile/stevenrombauts>
 * @package Nooku\Component\Comments
 */
class ModelComments extends Library\ModelDatabase
{
	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);

        $this->getState()
			->insert('table', 'cmd')
			->insert('row'  , 'int');
	}

    /**
     * Builds SELECT columns list for the query.
     *
     * @param   Library\DatabaseQuerySelect  A query object.
     * @return  void
     */
    protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);
        $state = $this->getState();

        $query->columns(array(
            'created_by_name' => 'creator.name'
        ));
    }

    /**
     * Builds LEFT JOINS clauses for the query.
     *
     * @param   Library\DatabaseQuerySelect  A query object.
     * @return  void
     */
    protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
    {
        $state = $this->getState();

        $query->join(array('creator' => 'users'), 'creator.users_user_id = tbl.created_by');
    }
	
	protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
	{
		parent::_buildQueryWhere($query);
		
		if(!$this->getState()->isUnique())
        {
			if($this->getState()->table) {
				$query->where('tbl.table = :table')->bind(array('table' => $this->getState()->table));
			}

			if($this->getState()->row) {
				$query->where('tbl.row = :row')->bind(array('row' => $this->getState()->row));
			}
		}
	}
}