<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Library;

/**
 * Files Html View
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Component\Files
 */
class FilesViewDirectoryHtml extends Library\ViewHtml
{
	public function render()
	{
        $page = $this->getObject('application.pages')->getActive();
        $params = new JParameter($page->params);

        $folders       = $this->_getFolders();
        $this->folders = $folders['items'];

        $files       = $this->_getFiles();
        $this->files = $files['items'];
        $this->total = $files['total'];

        $folder = $this->getModel()->getRow();

        if ($page->getLink()->query['folder'] !== $folder->path)
		{
			$path   = explode('/', $folder->path);
			$parent = count($path) > 1 ? implode('/', array_slice($path, 0, count($path)-1)) : '';

            $params->set('page_title', ucfirst(end($path)));
		} else {
            $parent = null;

            $params->set('page_title', $page->title);
        }

        $this->parent         = $parent;
        $this->params         = $params;
        $this->page           = $page;
        $this->thumbnail_size = array('x' => 200, 'y' => 150);

		$this->setPathway();

		return parent::render();
	}

    protected function _getFolders()
    {
        $page   = $this->getObject('application.pages')->getActive();
        $params = new JParameter($page->params);

        if ($params->get('show_folders', 1))
        {
            $state = $this->getModel()->getState();

            $identifier       = clone $this->getIdentifier();
            $identifier->path = array('model');
            $identifier->name = Library\StringInflector::pluralize($this->getName());
            $model            = $this->getObject($identifier)->container($state->container)->folder($state->folder);
            $folders          = $model->getRowset();
            $total            = $model->getTotal();

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
        $params = new JParameter($page->params);

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

        $identifier       = clone $this->getIdentifier();
        $identifier->path = array('controller');
        $identifier->name = 'file';
        $controller       = $this->getObject($identifier, array('request' => $request));

        $files = $controller->browse();
        $total = $controller->getModel()->getTotal();

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
            $folder = $this->getModel()->getRow();

			$pathway = $this->getObject('application')->getPathway();
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

				$pathway->addItem($part, $link);
			}
		}
	}
}