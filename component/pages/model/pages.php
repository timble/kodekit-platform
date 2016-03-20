<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Pages Model
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Pages
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
            ->insert('default', 'boolean')
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
        if (isset($context->state->type) && $context->state->type)
        {
            $entity->type = $context->state->type['name'];

            //Unsetting the type. We don't need it anymore
            $type = $context->state->type;
            unset($type['name']);

            $entity->state = http_build_query($type, '');
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

        if ($state->default) {
            $query->where('tbl.default = :default')->bind(array('default' => (int)$state->default));
        }

        if ($state->menu) {
            $query->where('tbl.pages_menu_id = :menu')->bind(array('menu' => $state->menu));
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

        if ($state->application) {
            $query->where('menus.application = :application')->bind(array('application' => $state->application));
        }
    }
}
