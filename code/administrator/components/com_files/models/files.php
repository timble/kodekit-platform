<?php
/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Files Model Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */

class ComFilesModelFiles extends ComFilesModelNodes
{
    public function getList()
    {
        if (!isset($this->_list))
        {
            $state = $this->_state;

            $files = $state->container->getAdapter('iterator')->getFiles(array(
        		'path'    => $this->_getPath(),
        		'exclude' => array('.svn', '.htaccess', '.git', 'CVS', 'index.html', '.DS_Store', 'Thumbs.db', 'Desktop.ini'),
        		'filter'  => array($this, 'iteratorFilter'),
        		'map'     => array($this, 'iteratorMap')
        	));
        	if ($files === false) {
        		throw new KModelException('Invalid folder');
        	}
            $this->_total = count($files);

            $files = array_slice($files, $state->offset, $state->limit ? $state->limit : $this->_total);

            if (strtolower($this->_state->direction) == 'desc') {
                $files = array_reverse($files);
            }

            $data = array();
            foreach ($files as $file)
            {
                $data[] = array(
                	'container' => $state->container,
                	'folder' => $state->folder,
                	'name' => $file
                );
            }

            $this->_list = $this->getRowset(array(
                'data' => $data
            ));
        }

        return parent::getList();
    }

	public function iteratorMap($path)
	{
		return basename($path);
	}

	public function iteratorFilter($path)
	{
		$filename = basename($path);
		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		if ($this->_state->name) {
			if (!in_array($filename, (array) $this->_state->name)) {
				return false;
			}
		}

		if ($this->_state->types) 
        {
			if ((in_array($extension, ComFilesDatabaseRowFile::$image_extensions) && !in_array('image', (array) $this->_state->types))
			|| (!in_array($extension, ComFilesDatabaseRowFile::$image_extensions) && !in_array('file', (array) $this->_state->types))
			) {
				return false;
			}
		}
		if ($this->_state->search && stripos($filename, $this->_state->search) === false) return false;
	}
}
