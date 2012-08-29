<?php
/**
 * @version     $Id: pages.php 3216 2011-11-28 15:33:44Z kotuha $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Pages Model Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */
class ComPagesModelPages extends ComPagesModelClosures
{
    protected $_page_xml;

    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->remove('sort')->insert('sort', 'cmd', 'custom')
            ->insert('enabled'   , 'boolean')
            ->insert('menu'      , 'int')
            ->insert('type'      , 'cmd')
            ->insert('home'      , 'boolean')
            ->insert('trashed'   , 'int')
            ->insert('access'    , 'int');
    }

    protected function _buildQueryColumns(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);

        $query->columns(array('component_name' => 'component.name'));

        $table = $this->getTable();
        if($table->isOrderable() && !$query->isCountQuery())
        {
            $state   = $this->getState();
            $columns = $table->getBehavior('orderable')->getColumns();

            if(in_array($state->sort, $columns))
            {
                if($state->sort == 'custom') {
                    $query->columns(array('ordering' => 'orderings.custom'));
                }

                $subquery = $this->getService('koowa:database.query.select')
                    ->columns('CHAR_LENGTH(MAX('.$state->sort.'))')
                    ->table($this->getTable()->getOrderingTable());

                $query->columns(array('ordering_path' => 'GROUP_CONCAT(LPAD(ordering_crumbs.'.$state->sort.', ('.$subquery.'), \'0\') ORDER BY crumbs.level DESC  SEPARATOR \'/\')'));
            }
        }
    }

    protected function _buildQueryJoins(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryJoins($query);

        $query->join(array('component' => 'extensions_components'), 'component.id = tbl.extensions_component_id');

        $table = $this->getTable();
        if($table->isOrderable() && !$query->isCountQuery())
        {
            $state          = $this->getState();
            $columns        = $table->getBehavior('orderable')->getColumns();
            $id_column      = $table->getIdentityColumn();
            $ordering_table = $table->getOrderingTable();

            // This one is to have a breadcrumbs style order like 1/3/4.
            if(in_array($state->sort, $columns))
            {
                // This one is to display the custom ordering in backend.
                if($state->sort == 'custom') {
                    $query->join(array('orderings' => $ordering_table), 'tbl.'.$id_column.' = orderings.'.$id_column);
                }

                $query->join(array('ordering_crumbs' => $ordering_table), 'crumbs.ancestor_id = ordering_crumbs.'.$id_column, 'INNER');
            }
        }
    }

    protected function _buildQueryWhere(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();

        if($state->home) {
            $query->where('tbl.home = :home')->bind(array('home' => (int) $state->home));
        }

        if($state->menu) {
            $query->where('tbl.pages_menu_id = :menu_id')->bind(array('menu_id' => $state->menu));
        }

        if(is_bool($state->enabled)) {
            $query->where('tbl.enabled = :enabled')->bind(array('enabled' => (int) $state->enabled));
        }

        if(is_numeric($state->access)) {
            $query->where('tbl.access = :access')->bind(array('access' => $state->access));
        }

        //if(is_numeric($state->trashed)) {
        //  $query->where('tbl.trashed','=', $state->trashed);
        //}

        if(is_numeric($state->group_id)) {
            $query->where('tbl.group_id','=', $state->group_id);
        }

        if($state->search) {
            $query->where('tbl.title LIKE :search')->bind(array('search' => '%'.$state->search.'%'));
        }
    }

    protected function _buildQueryOrder(KDatabaseQuerySelect $query)
    {
        $state = $this->getState();
        $table = $this->getTable();

        if($table->isOrderable() && in_array($state->sort, $table->getBehavior('orderable')->getColumns())) {
            $query->order('ordering_path', 'ASC');
        } else {
            parent::_buildQueryWhere($query);
        }
    }
}
