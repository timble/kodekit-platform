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
 * Nodes Model
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ModelNodes extends ModelAbstract
{
    protected $_container;

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'identity_key' => 'name',
            'behaviors'    => array('paginatable'),
        ));

        parent::_initialize($config);
    }

    protected function _actionCreate(Library\ModelContext $context)
    {
        $entity = parent::_actionCreate($context);

        $entity->setProperties(array(
            'container' => $context->state->container,
            'folder'    => $context->state->folder,
            'name'      => $context->state->name
        ));

        return $entity;
    }

    protected function _actionFetch(Library\ModelContext $context)
    {
        $state = $context->state;
        $type = !empty($state->types) ? (array)$state->types : array();

        $list = $this->getObject('com:files.model.entity.nodes');

        // Special case for limit=0. We set it to -1 so loop goes on till end since limit is a negative value
        $limit_left  = $state->limit ? $state->limit : -1;
        $offset_left = $state->offset;
        $total       = 0;

        if (empty($type) || in_array('folder', $type))
        {
            $folders = $this->getObject('com:files.model.folders')->setState($state->getValues());

            foreach ($folders->fetch() as $folder)
            {
                if (!$limit_left) {
                    break;
                }

                $list->insert($folder);
                $limit_left--;
            }

            $total += $folders->count();
            $offset_left -= $total;
        }

        if ((empty($type) || (in_array('file', $type) || in_array('image', $type))))
        {
            $data           = $state->getValues();
            $data['offset'] = $offset_left < 0 ? 0 : $offset_left;

            $files = $this->getObject('com:files.model.files')->setState($data);

            foreach ($files->fetch() as $file)
            {
                if (!$limit_left) {
                    break;
                }

                $list->insert($file);
                $limit_left--;
            }

            $total += $files->count();
        }

        $this->_count = $total;

        return $list;
    }

    protected function _actionCount(Library\ModelContext $context)
    {
        if (!isset($this->_count)) {
            $this->fetch();
        }

        return $this->_count;
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

    protected function _getPath()
    {
        $state = $this->getState();
        $path  = $this->getContainer()->path;

        if (!empty($state->folder) && $state->folder != '/') {
            $path .= '/' . ltrim($state->folder, '/');
        }

        return $path;
    }
}
