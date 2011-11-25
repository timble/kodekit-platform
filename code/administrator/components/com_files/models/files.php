<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Files Model Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesModelFiles extends ComFilesModelNodes
{   
    public function getItem()
    {
        if (!isset($this->_item))
        {
            $this->_item = $this->getRow(array(
                'data' => array(
            		'container' => $this->_state->container,
                    'basepath' => $this->_getPath(),
                    'path' => $this->_state->path
                )
            ));
        }

        return parent::getItem();
    }

    public function getList()
    {
        if (!isset($this->_list))
        {
            $state = $this->_state;

            $files = ComFilesIteratorDirectory::getFiles(array(
        		'path' => $this->_getPath(),
        		'exclude' => array('.svn', '.htaccess', '.git', 'CVS', 'index.html', '.DS_Store', 'Thumbs.db', 'Desktop.ini'),
        		'filter' => array($this, 'iteratorFilter'),
        		'map' => array($this, 'iteratorMap')
        	));
            $this->_total = count($files);
        
    		$path = $state->path;
    	    if (!empty($path)) 
    	    {
    	        $f = array();
    	        foreach ((array) $path as $file) {
                    if (in_array($path, $files)) {
                        $f[] = $path;
                    }
                }
                $files = $f;
    	    }

            $files = array_slice($files, $state->offset, $state->limit ? $state->limit : $this->_total);

            if (strtolower($this->_state->direction) == 'desc') {
                $files = array_reverse($files);
            }

            $data = array();
            foreach ($files as $file)
            {
                $data[] = array(
                	'container' => $state->container,
                    'basepath' => $state->container->path,
                    'path' => $file
                );
            }

            $this->_list = $this->getRowset(array(
                'data' => $data
            ));
        }

        return parent::getList();
    }

	public function iteratorMap($file)
	{
		$path = str_replace('\\', '/', $file->getPathname());
		$path = str_replace($this->_state->container->path.'/', '', $path);

		return $path;
	}

	public function iteratorFilter($file)
	{
		if ($this->_state->types) {
			if ((in_array($file->getExtension(), ComFilesDatabaseRowFile::$image_extensions) && !in_array('image', (array) $this->_state->types))
			|| (!in_array($file->getExtension(), ComFilesDatabaseRowFile::$image_extensions) && !in_array('file', (array) $this->_state->types))
			) {
				return false;
			}
		}
		if ($this->_state->search && stripos($file->getFilename(), $this->_state->search) === false) return false;
	}
}
