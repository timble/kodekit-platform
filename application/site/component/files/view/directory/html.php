<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Files;

use Kodekit\Library;

/**
 * Files Html View
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Kodekit\Platform\Files
 */
class ViewDirectoryHtml extends Library\ViewHtml
{
    protected function _actionRender(Library\ViewContext $context)
    {
        $this->_setPathway();

        return parent::_actionRender($context);
    }

    protected function _fetchData(Library\ViewContext $context)
    {
        $page   = $this->getObject('pages')->getActive();
        $params = $page->getParams('page');

        $folders       = $this->_getFolders();
        $context->data->folders = $folders['items'];

        $files       = $this->_getFiles();
        $context->data->files = $files['items'];
        $context->data->total = $files['total'];

        $folder = $this->getModel()->fetch();

        if ($page->getLink()->query['folder'] !== $folder->path)
        {
            $path   = explode('/', $folder->path);
            $parent = count($path) > 1 ? implode('/', array_slice($path, 0, count($path)-1)) : '';

            $params->set('page_title', ucfirst(end($path)));
        }
        else
        {
            $parent = null;
            $params->set('page_title', $page->title);
        }

        $context->data->parent         = $parent;
        $context->data->params         = $params;
        $context->data->page           = $page;
        $context->data->thumbnail_size = array('x' => 200, 'y' => 150);

        parent::_fetchData($context);
    }

    protected function _getFolders()
    {
        $page   = $this->getObject('pages')->getActive();
        $params = $page->getParams('page');

        if ($params->get('show_folders', 1))
        {
            $state = $this->getModel()->getState();

            $identifier         = $this->getIdentifier()->toArray();
            $identifier['path'] = array('model');
            $identifier['name'] = Library\StringInflector::pluralize($this->getName());

            $model            = $this->getObject($identifier)->container($state->container)->folder($state->folder);
            $folders          = $model->fetch();
            $total            = $model->count();

            if ($params->get('humanize_filenames', 1))
            {
                foreach ($folders as $folder) {
                    $folder->display_name = ucfirst(preg_replace('#[-_\s\.]+#i', ' ', $folder->name));
                }
            }
        }
        else
        {
            $folders = array();
            $total   = 0;
        }

        return array('items' => $folders, 'total' => $total);
    }

    protected function _getFiles()
    {
        $page   = $this->getObject('pages')->getActive();
        $params = $page->getParams('page');

        $state = $this->getModel()->getState();

        $query = array(
            'thumbnails' => true,
            'sort'       => $params->get('sort'),
            'direction'  => $params->get('direction'),
            'offset'     => $state->offset,
            'folder'     => $state->folder,
            'container'  => $state->container,
            'limit'      => $state->limit,
        );

        if ($this->getLayout() == 'gallery') {
            $query['type'] = array('image');
        }

        $identifier         = $this->getIdentifier()->toArray();
        $identifier['path'] = array('controller');
        $identifier['name'] = 'file';
        $controller         = $this->getObject($identifier, array('request' => array('query' => $query)));

        $files = $controller->browse();
        $total = $controller->getModel()->count();

        if ($params->get('humanize_filenames', 1))
        {
            foreach ($files as $file) {
                $file->display_name = ucfirst(preg_replace('#[-_\s\.]+#i', ' ', $file->filename));
            }
        }

        return array('items' => $files, 'total' => $total);
    }

    protected function _setPathway()
    {
        if ($this->parent !== null)
        {
            $folder = $this->getModel()->fetch();

            $path    = $folder->path;
            $query   = $this->page->getLink()->query;

            if (!empty($query['folder']) && strpos($path, $query['folder']) === 0) {
                $path = substr($path, strlen($query['folder'])+1, strlen($path));
            }

            $parts = explode('/', $path);

            foreach ($parts as $i => $part)
            {
                $link = '';
                if ($part !== $folder->name)
                {
                    $path = implode('/', array_slice($parts, 0, $i+1));
                    $link = 'view=directory&folder='.$path;
                }

                $pathway = $this->getObject('pages')->getPathway();
                $pathway[] = array(
                    'title' => $parts,
                    'link'  => $link,
                );
            }
        }
    }
}
