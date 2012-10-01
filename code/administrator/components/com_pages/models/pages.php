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
class ComPagesModelPages extends ComDefaultModelDefault
{
    protected $_page_xml;

    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->remove('sort')->insert('sort', 'cmd', 'path')
            ->insert('published' , 'boolean')
            ->insert('menu'      , 'int')
            ->insert('type'      , 'cmd')
            ->insert('home'      , 'boolean')
            ->insert('trashed'   , 'int')
            ->insert('access'    , 'int');
    }

    protected function _buildQueryColumns(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);

        $query->columns(array('component_name' => 'components.name'));
    }

    protected function _buildQueryJoins(KDatabaseQuerySelect $query)
    {
        parent::_buildQueryJoins($query);

        $query->join(array('components' => 'extensions_components'), 'components.extensions_component_id = tbl.extensions_component_id');
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

        if(is_bool($state->published)) {
            $query->where('tbl.published = :published')->bind(array('published' => (int) $state->published));
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
}
