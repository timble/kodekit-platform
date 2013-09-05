<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Files Model
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
            $state = $this->getState();

            $files = $this->getContainer()->getAdapter('iterator')->getFiles(array(
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
            
            if (strtolower($this->getState()->direction) == 'desc') {
            	$files = array_reverse($files);
            }
            
            $files = array_slice($files, $state->offset, $state->limit ? $state->limit : $this->_total);

            $data = array();
            foreach ($files as $file)
            {
                $data[] = array(
                	'container' => $state->container,
                	'folder'    => $state->folder,
                	'name'      => $file
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

		if ($this->getState()->name)
        {
			if (!in_array($filename, (array) $this->getState()->name)) {
				return false;
			}
		}

		if ($this->getState()->types)
        {
			if ((in_array($extension, DatabaseRowFile::$image_extensions) && !in_array('image', (array) $this->getState()->types))
			|| (!in_array($extension, DatabaseRowFile::$image_extensions) && !in_array('file', (array) $this->getState()->types))
			) {
				return false;
			}
		}

		if ($this->getState()->search && stripos($filename, $this->getState()->search) === false) {
            return false;
        }
	}
}
