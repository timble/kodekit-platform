<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Pages Model
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class ModelPages extends Library\ModelTable
{
    protected $_page_xml;

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('sort'       , 'cmd', 'custom')
            ->insert('published'  , 'boolean')
            ->insert('menu'       , 'int')
            ->insert('type'       , 'cmd')
            ->insert('home'       , 'boolean')
            ->insert('trashed'    , 'int')
            ->insert('access'     , 'int')
            ->insert('hidden'     , 'boolean')
            ->insert('application', 'word');
    }

    protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);

        if(!$query->isCountQuery()) {
            $query->columns(array('extension_name' => 'extensions.name'));
        }
    }

    protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryJoins($query);

        if(!$query->isCountQuery()) {
            $query->join(array('extensions' => 'extensions'), 'extensions.extensions_extension_id = tbl.extensions_extension_id');
        }

        $state = $this->getState();
        if($state->application) {
            $query->join(array('menus' => 'pages_menus'), 'menus.pages_menu_id = tbl.pages_menu_id', 'RIGHT');
        }
    }

    protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
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
            $query->where('tbl.access <= :access')->bind(array('access' => $state->access));
        }
        
        if(is_bool($state->hidden)) {
            $query->where('tbl.hidden = :hidden')->bind(array('hidden' => (int) $state->hidden));
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

        if($state->application) {
            $query->where('menus.application = :application')->bind(array('application' => $state->application));
        }
    }
}
