<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-articles for the canonical source repository
 */

namespace Kodekit\Component\Articles;

use Kodekit\Library;

/**
 * Articles Model
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Articles
 */
class ModelArticles extends Library\ModelDatabase
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('published', 'int')
            ->insert('created_by', 'int')
            ->insert('access', 'int')
            ->insert('sort', 'cmd', 'ordering');
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

        $query->columns(array(
            'thumbnail'     => 'thumbnails.thumbnail',
            'ordering_date' => 'IF(tbl.publish_on, tbl.publish_on, tbl.created_on)'
        ));
    }

    protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryJoins($query);

        $query->join(array('attachments' => 'attachments'), 'attachments.attachments_attachment_id = tbl.attachments_attachment_id')
              ->join(array('thumbnails' => 'files_thumbnails'), 'thumbnails.filename = attachments.path');
    }

    protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);

        $state = $this->getState();

        if (is_numeric($state->published)) {
            $query->where('tbl.published = :published')->bind(array('published' => (int)$state->published));
        }

        if ($state->created_by) {
            $query->where('tbl.created_by = :created_by')->bind(array('created_by' => $state->created_by));
        }

        if (is_numeric($state->access)) {
            $query->where('tbl.access <= :access')->bind(array('access' => $state->access));
        }
    }
}