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
 * Files Html View Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class FilesViewDirectoryHtml extends Library\ViewHtml
{
	public function render()
	{
        $page = $this->getObject('application.pages')->getActive();
        $params = new JParameter($page->params);

        $this->folders = $this->_getFolders();

		if ($params->get('humanize_filenames', 1)) 
		{
			foreach ($this->folders as $folder) {
				$folder->display_name = ucfirst(preg_replace('#[-_\s\.]+#i', ' ', $folder->name));
			}
			
			foreach ($this->files as $file) {
                $file->display_name = ucfirst(preg_replace('#[-_\s\.]+#i', ' ', $file->filename));
			}
		}
	
        $folder = $this->getModel()->getRow();

        if ($page->getLink()->query['folder'] !== $folder->path)
		{
			$path   = explode('/', $folder->path);
			$parent = count($path) > 1 ? implode('/', array_slice($path, 0, count($path)-1)) : '';
		} else {
            $parent = null;
        }

	 	if (!$params->get('page_title')) {
	 		$params->set('page_title', $page->title);
	 	}

        $this->parent  = $parent;
		$this->params  = $params;
		$this->page    = $page;
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
        }
        else
        {
            $folders = array();
        }

        return $folders;
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