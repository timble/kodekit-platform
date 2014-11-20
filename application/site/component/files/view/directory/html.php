<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Files Html View
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Component\Files
 */
class FilesViewDirectoryHtml extends Library\ViewHtml
{
    protected function _actionRender(Library\ViewContext $context)
    {
        $this->setPathway();

        return parent::_actionRender($context);
    }

    protected function _fetchData(Library\ViewContext $context)
    {
        $page   = $this->getObject('application.pages')->getActive();
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
        $page   = $this->getObject('application.pages')->getActive();
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
        $page   = $this->getObject('application.pages')->getActive();
        $params = $page->getParams('page');

        $state = $this->getModel()->getState();

        $request = $this->getObject('lib:controller.request');

        if ($this->getLayout() == 'gallery') {
            $request->query->set('types', array('image'));
        }

        $request->query->set('thumbnails', true);
        $request->query->set('sort', $params->get('sort'));
        $request->query->set('direction', $params->get('direction'));
        $request->query->set('offset', $state->offset);
        $request->query->set('folder', $state->folder);
        $request->query->set('container', $state->container);
        $request->query->set('limit', $state->limit);

        $identifier         = $this->getIdentifier()->toArray();
        $identifier['path'] = array('controller');
        $identifier['name'] = 'file';
        $controller         = $this->getObject($identifier, array('request' => $request));

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

    public function setPathway()
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
                if ($part !== $folder->name)
                {
                    $path = implode('/', array_slice($parts, 0, $i+1));
                    $link = $this->getRoute('&view=directory&folder='.$path);
                }
                else $link = '';

                $this->getObject('com:pages.pathway')->addItem($part, $link);
            }
        }
    }
}