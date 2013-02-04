<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Files Html View Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComFilesViewFolderHtml extends ComDefaultViewHtml
{
	public function display()
	{
		$folder = $this->getModel()->getRow();

		$state 	= $this->getModel()->getState();
		$page 	= $this->getService('application.pages')->getActive();
		$params = new JParameter($page->params);
	
		if ($params->get('limit') != -1) {
			$state->limit = (int) $params->get('limit');
		}
	
		$state->sort = $params->get('sort');
		$state->direction = $params->get('direction');
	
		$state_data = $state->toArray();
		$state_data['folder'] = isset($state_data['folder']) ? rawurldecode($state_data['folder']) : '';
		
		if ($state->name) {
			$state_data['folder'] .= '/'.$state->name;
			unset($state_data['name']);
		}

		if ($this->getLayout() === 'gallery') {
			$state_data['types'] = array('image');
		}
		
		$state_data = $this->getService('koowa:controller.request', array(
			'query' => $state_data
		));
	
		$folders = array();
		if ($params->get('show_folders', 1))
		{
			$folders = $this->getService('com://admin/files.controller.folder', array(
				'request' => $state_data
			))->browse();
		}
		
		$state_data->query->set('thumbnails', (bool) $params->get('show_thumbnails'));
		
		$file_controller = $this->getService('com://admin/files.controller.file', array(
			'request' => $state_data
		));
	
		$files = $file_controller->browse();
		$total = $file_controller->getModel()->getTotal();

		foreach ($folders as $f)
		{
			if ($params->get('humanize_filenames', 1)) {
				$f->display_name = ucfirst(preg_replace('#[-_\s\.]+#i', ' ', $f->name));
			} else {
				$f->display_name = $f->name;
			}
		}
	
		foreach ($files as $f)
		{
			if ($params->get('humanize_filenames', 1)) {
				$f->display_name = ucfirst(preg_replace('#[-_\s\.]+#i', ' ', $f->filename));
			} else {
				$f->display_name = $f->name;
			}
		}
	
		$parent = null;
		if ($page->query['folder'] !== $folder->path)
		{
			$path   = explode('/', $folder->path);
			$parent = count($path) > 1 ? implode('/', array_slice($path, 0, count($path)-1)) : '';
		}
	
		$params->page_title = $params->get('page_title') ? $params->get('page_title') : (isset($page->name) ? $page->name : $page->title);
	
		$this->folder  = $folder;
		$this->files   = $files;
		$this->folders = $folders;
		$this->total   = $total;
		$this->parent  = $parent;
		$this->params  = $params;
		$this->page    = $page;
		$this->thumbnail_size = array('x' => 200, 'y' => 150);
	
		//$this->setPathway();
	
		return parent::display();
	}
	
    public function display_category()
    {
        //Get the parameters
        $params = $this->getService('application')->getParams();

        //Get the category
        $category = $this->getService('com://site/articles.model.categories')
                         ->table('articles')
                         ->id($this->getModel()->getState()->category)
                         ->getRow();

        //Get the parameters of the active menu item
        if($page = $this->getService('application.pages')->getActive())
        {
            $page_params = new JParameter($page->params);
            if(!$page_params->get('page_title')) {
                $params->set('page_title', $category->title);
            }
        }
        else $params->set('page_title',	$category->title);

        //Set the pathway
        if($page->query['view'] == 'categories' ) {
            $this->getService('application')->getPathway()->addItem($category->title, '');
        }

        $this->assign('params'  , $params);
        $this->assign('category', $category);

        return parent::display();
    }
}