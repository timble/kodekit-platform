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

class ComFilesModelFiles extends ComFilesModelDefault
{
    public function getItem()
    {
        if (!isset($this->_item))
        {
            $this->_item	= KFactory::tmp('admin::com.files.database.row.file', array(
                'data' => array(
            		'container' => $this->_state->container,
                    'basepath' => $this->_state->basepath,
                    'path' => $this->_state->path
                )));
        }

        return parent::getItem();
    }

    public function getList()
    {
        if (!isset($this->_list))
        {
            $state = $this->_state;
            if (!$state->basepath) {
                throw new KModelException('Basepath is not a valid folder');
            }

            $basepath = $state->basepath;
            $path = $basepath;

            if (!empty($state->folder) && $state->folder != '/') {
                $path .= '/'.ltrim($state->folder, '/');
            }

            if (!is_dir($path)) {
                throw new KModelException('Basepath is not a valid folder');
            }

            $name = $state->path ? $state->path : null;
            if (is_string($name))
            {
                $files[] = $name;
            }
            else if (is_array($name))
            {
                $files = array();
                foreach ($name as $n) {
                    $files[] = $n;
                }
            }
            else
            {
            	$files = ComFilesIteratorDirectory::getFiles(array(
            		'path' => $path,
            		'exclude' => array('.svn', '.htaccess', '.git', 'CVS', 'index.html', '.DS_Store', 'Thumbs.db', 'Desktop.ini'),
            		'filter' => array($this, 'iteratorFilter'),
            		'map' => array($this, 'iteratorMap')
            	));

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
                    'basepath' => $basepath,
                    'path' => $file
                );
            }

            $this->_list = KFactory::tmp('admin::com.files.database.rowset.files', array(
                'data' => $data
            ));
        }

        return parent::getList();
    }

	public function iteratorMap($file)
	{
		$path = str_replace('\\', '/', $file->getPathname());
		$path = str_replace($this->_state->basepath.'/', '', $path);

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

    public function getColumn($column)
    {
        return $this->getList();
    }
}
