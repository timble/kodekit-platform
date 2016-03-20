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
 * Menus Model
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Pages
 */
class ModelMenus extends Library\ModelDatabase
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('sort', 'cmd', 'title')
            ->insert('application', 'word');
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array('searchable'),
        ));

        parent::_initialize($config);
    }

    protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);

        if (!$query->isCountQuery()) {
            $query->columns(array('page_count' => 'COUNT(pages.pages_page_id)'));
        }
    }

    protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryJoins($query);

        if (!$query->isCountQuery()) {
            $query->join(array('pages' => 'pages'), 'tbl.pages_menu_id = pages.pages_menu_id');
        }
    }

    protected function _buildQueryGroup(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryGroup($query);

        if (!$query->isCountQuery()) {
            $query->group('pages.pages_menu_id');
        }
    }

    protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);
        $state = $this->getState();

        if (!$state->isUnique()) {
            if ($state->application) {
                $query->where('tbl.application = :application')->bind(array('application' => $state->application));
            }
        }
    }
}
