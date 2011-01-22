<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Versions
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Revisions Model
 *
 * @author      Torkil Johnsen <torkil@bedre.no>
 * @author      Johan Janssens <johan@nooku.org>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Versions
 */
class ComVersionsModelRevisions extends ComModelDefaultModel
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_state
			->insert('status'  , 'cmd');
	}

    /**
     * Get a revision item
     *
     * When gettig revision number X, this method will transparently build the
     * data from revision 1 to X and return a complete row. This is done because
     * revision X always contains just the changes from the previous revision,
     * so the rest needs to be built.
     *
     * @return KDatabaseRow
     */
    public function getItem()
    {
        if (!isset($this->_item))
        {
            if ($this->_state->revision > 1) {
                $this->_item = $this->getRevision();
            }
        }

        return parent::getItem();
    }

    /**
     * Get a complete revision row, merging data from all previous revisions
     *
     * @return KDatabaseRow
     */
    public function getRevision()
    {
        $revisions = $this->getList();
        $data      = array();

        foreach ($revisions as $row)
        {
            if (!isset($revision)) {
                $revision = $row;
            }

            $data = array_merge(json_decode($row->data, true), $data);
        }

        $revision->data = json_encode((object)$data);

        return $revision;
    }

    protected function _buildQueryColumns(KDatabaseQuery $query)
    {
        $query->columns[] = 'u.name AS user_name';
        parent::_buildQueryColumns($query);
    }

    protected function _buildQueryJoins(KDatabaseQuery $query)
    {
        $query->join('RIGHT', 'users u', 'tbl.created_by = u.id');
    }

    /**
     * Build where part of query
     *
     * When getting a revision X > 1, we need to get all revisions from
     * 1 to X, and combine the data from these into one row.
     *
     * @param KDatabaseQuery $query
     */
    protected function _buildQueryWhere(KDatabaseQuery $query)
    {
    	parent::_buildQueryWhere($query);

    	$state = $this->_state;

        if($state->revision > 1) {
            $state->revision = range(1, $state->revision);
        }

        if($state->status) {
        	$query->where('tbl.status' , '=', $state->status);
        }

        if($state->table && !$state->isUnique()) {
        	$query->where('tbl.table', '=', $state->table);
        }
    }

    protected function _buildQueryOrder(KDatabaseQuery $query)
    {
        $query->order('tbl.revision', 'desc');
    }
}