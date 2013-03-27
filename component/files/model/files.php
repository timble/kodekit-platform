<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Files Model Class
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ModelFiles extends ModelNodes
{
    public function getRowset()
    {
        if (!isset($this->_rowset))
        {
            $state = $this->_state;

            $files = $state->container->getAdapter('iterator')->getFiles(array(
        		'path'    => $this->_getPath(),
        		'exclude' => array('.svn', '.htaccess', '.git', 'CVS', 'index.html', '.DS_Store', 'Thumbs.db', 'Desktop.ini'),
        		'filter'  => array($this, 'iteratorFilter'),
        		'map'     => array($this, 'iteratorMap'),
                'sort'    => $state->sort
        	));

        	if ($files === false) {
        		throw new \UnexpectedValueException('Invalid folder');
        	}

            $this->_total = count($files);
            
            if (strtolower($this->_state->direction) == 'desc') {
            	$files = array_reverse($files);
            }
            
            $files = array_slice($files, $state->offset, $state->limit ? $state->limit : $this->_total);

            $data = array();
            foreach ($files as $file)
            {
                $data[] = array(
                	'container' => $state->container,
                	'folder' => $state->folder,
                	'name' => $file
                );
            }

            $this->_rowset = $this->createRowset(array(
                'data' => $data
            ));
        }

        return parent::getRowset();
    }

	public function iteratorMap($path)
	{
		return basename($path);
	}

	public function iteratorFilter($path)
	{
		$filename = basename($path);
		$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

		if ($this->_state->name) {
			if (!in_array($filename, (array) $this->_state->name)) {
				return false;
			}
		}

		if ($this->_state->types) 
        {
			if ((in_array($extension, DatabaseRowFile::$image_extensions) && !in_array('image', (array) $this->_state->types))
			|| (!in_array($extension, DatabaseRowFile::$image_extensions) && !in_array('file', (array) $this->_state->types))
			) {
				return false;
			}
		}
		if ($this->_state->search && stripos($filename, $this->_state->search) === false) return false;
	}
}
