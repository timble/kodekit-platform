<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Files Html View Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComFilesViewDirectoryHtml extends ComBaseViewHtml
{
	public function render()
	{
		$state 	= $this->getModel()->getState();
		$state->container = 'files-files';
		
		$page 	= $this->getService('application.pages')->getActive();
		$params = new JParameter($page->params);

		$data   = $this->getService('lib://nooku/controller.request', array(
			'query' => array('container' => $state->container, 'folder' => $state->folder, 'name' => $state->name)
		));
		$folder = $this->getService('com://admin/files.controller.folder', array(
			'request' => $data
		))->read();

		if ($params->get('limit') > 0) {
			$state->limit = (int) $params->get('limit');
		}

		$state->sort = $params->get('sort');
		$state->direction = $params->get('direction');
	
		$request = $state->toArray();
		$request['folder'] = isset($request['folder']) ? rawurldecode($request['folder']) : '';
		
		if ($state->name) {
			$request['folder'] .= '/'.$state->name;
			unset($request['name']);
		}

		if ($this->getLayout() === 'gallery') {
			$request['types'] = array('image');
		}
		
		$request = $this->getService('lib://nooku/controller.request', array(
			'query' => $request
		));

		$folders = array();
		if ($params->get('show_folders', 1))
		{
			$clone = clone $request;
			$clone->query['limit'] = 0;
			$clone->query['offset'] = 0;

			$folders = $this->getService('com://admin/files.controller.folder', array(
				'request' => $clone
			))->browse();
		}
		
		$request->query->set('thumbnails', true);

		$file_controller = $this->getService('com://admin/files.controller.file', array(
			'request' => $request
		));

		$files = $file_controller->browse();
		$total = $file_controller->getModel()->getTotal();

		if ($params->get('humanize_filenames', 1)) 
		{
			foreach ($folders as $f) {
				$f->display_name = ucfirst(preg_replace('#[-_\s\.]+#i', ' ', $f->name));
			}
			
			foreach ($files as $f) {
				$f->display_name = ucfirst(preg_replace('#[-_\s\.]+#i', ' ', $f->filename));
			}
		}
	
		$parent = null;
		if ($page->getLink()->query['folder'] !== $folder->path)
		{
			$path   = explode('/', $folder->path);
			$parent = count($path) > 1 ? implode('/', array_slice($path, 0, count($path)-1)) : '';
		}

	 	if (!$params->get('page_title')) {
	 		$params->set('page_title', $page->title);
	 	}
	
		$this->folder  = $folder;
		$this->files   = $files;
		$this->folders = $folders;
		$this->total   = $total;
		$this->parent  = $parent;
		$this->params  = $params;
		$this->page    = $page;
		$this->thumbnail_size = array('x' => 200, 'y' => 150);
	
		$this->setPathway();
	
		return parent::render();
	}

	public function setPathway()
	{
		if ($this->parent !== null)
		{
			$state   = $this->getModel()->getState();
			$pathway = $this->getService('application')->getPathway();
			$path    = $this->folder->path;
			$query   = $this->page->getLink()->query;
		
			if (!empty($query['folder']) && strpos($path, $query['folder']) === 0) {
				$path = substr($path, strlen($query['folder'])+1, strlen($path));
			}
			$parts = explode('/', $path);

			foreach ($parts as $i => $part)
			{
				if ($part !== $this->folder->name)
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