<?php
/**
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Versions
 * @copyright	Copyright (C) 2010 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Revisions Model
 *
 * @author      Torkil Johnsen <http://nooku.assembla.com/profile/torkiljohnsen>
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package    	Nooku_Components
 * @subpackage 	Versions
 */
class ComVersionsModelRevisions extends ComDefaultModelDefault
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
    public function getRow()
    {
        if (!isset($this->_row))
        {
            if ($this->_state->revision > 1) {
                $this->_row = $this->getRevision();
            }
        }

        return parent::getRow();
    }

    /**
     * Get a complete revision row, merging data from all previous revisions
     *
     * @return KDatabaseRow
     */
    public function getRevision()
    {
        $revisions = $this->getRowset();
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

    protected function _buildQueryColumns(KDatabaseQuerySelect $query)
    {
        $query->columns[] = 'u.name AS user_name';
        parent::_buildQueryColumns($query);
    }

    protected function _buildQueryJoins(KDatabaseQuerySelect $query)
    {
        $query->join('RIGHT', 'users u', 'tbl.created_by = u.id');
    }

    /**
     * Build where part of query
     *
     * When getting a revision X > 1, we need to get all revisions from
     * 1 to X, and combine the data from these into one row.
     *
     * @param KDatabaseQuerySelect $query
     */
    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
    	parent::_buildQueryWhere($query);

    	$state = $this->_state;

        if($state->revision > 1) {
            $state->revision = range(1, $state->revision);
        }

        if($state->status) {
        	$query->where('tbl.status' , '=', $state->status);
        }
        
        if($state->row) {
        	$query->where('tbl.row' , 'IN', array($state->row));
        }

        if($state->table && !$state->isUnique()) {
        	$query->where('tbl.table', '=', $state->table);
        }
    }

    protected function _buildQueryOrder(KDatabaseQuerySelect $query)
    {
        $query->order('tbl.revision', 'desc');
    }
}