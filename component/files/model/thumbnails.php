<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Thumbnails Model
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ModelThumbnails extends Library\ModelDatabase
{
    protected $_container;

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('container', 'com:files.filter.container', null)
            ->insert('folder'   , 'com:files.filter.path')
            ->insert('filename' , 'com:files.filter.path' /*, null, true, array('container')*/)
            ->insert('source'   , 'raw', null, true)
            ->insert('types'    , 'cmd', '')
            ->insert('config'   , 'json', '');
    }

    public function getContainer()
    {
        if (!isset($this->_container))
        {
            //Set the container
            $container = $this->getObject('com:files.model.containers')->slug($this->getState()->container)->fetch();

            if (!is_object($container) || $container->isNew()) {
                throw new \UnexpectedValueException('Invalid container');
            }

            $this->_container = $container;
        }

        return $this->_container;
    }

    protected function _buildQueryColumns(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryColumns($query);

        $state = $this->getState();

        if ($state->container) {
            $query->columns(array('container' => 'containers.slug'));
        }
    }

    protected function _buildQueryJoins(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryJoins($query);

        $state = $this->getState();

        if ($state->container) {
            $query->join(array('containers' => 'files_containers'), 'containers.files_container_id = tbl.files_container_id');
        }
    }

    protected function _buildQueryWhere(Library\DatabaseQuerySelect $query)
    {
        parent::_buildQueryWhere($query);

        $state = $this->getState();

        if ($state->container) {
            $query->where('tbl.files_container_id = :container_id')->bind(array('container_id' => $this->getContainer()->id));
        }

        if ($state->folder !== false) {
            $query->where('tbl.folder = :folder')->bind(array('folder' => ltrim($state->folder, '/')));
        }

        if ($state->filename) {
            $query->where('tbl.filename IN :filename')->bind(array('filename' => (array)$state->filename));
        }
    }
}
