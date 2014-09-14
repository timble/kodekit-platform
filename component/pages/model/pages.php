<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Pages Model
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class ModelPages extends Library\ModelDatabase
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('sort', 'cmd', 'custom')
            ->insert('published', 'boolean')
            ->insert('menu', 'int')
            ->insert('type', 'cmd')
            ->insert('home', 'boolean')
            ->insert('access', 'int')
            ->insert('hidden', 'boolean')
            ->insert('application', 'word');
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array('closurable', 'searchable'),
        ));

        parent::_initialize($config);
    }

    protected function _actionCreate(Library\ModelContext $context)
    {
        $entity = parent::_actionCreate($context);

        //Set the page properties based on the model state information
        if (isset($context->state->type) && $context->state->type) {
            $entity->type     = $context->state->type['name'];
            $entity->link_url = http_build_query($context->state->type, '');
        }

        return $entity;
    }

    protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryJoins($query);

        $state = $this->getState();
        if ($state->application) {
            $query->join(array('menus' => 'pages_menus'), 'menus.pages_menu_id = tbl.pages_menu_id', 'RIGHT');
        }
    }

    protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();

        if ($state->home) {
            $query->where('tbl.home = :home')->bind(array('home' => (int)$state->home));
        }

        if ($state->menu) {
            $query->where('tbl.pages_menu_id = :menu_id')->bind(array('menu_id' => $state->menu));
        }

        if (is_bool($state->published)) {
            $query->where('tbl.published = :published')->bind(array('published' => (int)$state->published));
        }

        if (is_numeric($state->access)) {
            $query->where('tbl.access <= :access')->bind(array('access' => $state->access));
        }

        if (is_bool($state->hidden)) {
            $query->where('tbl.hidden = :hidden')->bind(array('hidden' => (int)$state->hidden));
        }

        if (is_numeric($state->group_id)) {
            $query->where('tbl.group_id', '=', $state->group_id);
        }

        if ($state->application) {
            $query->where('menus.application = :application')->bind(array('application' => $state->application));
        }
    }
}
