<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-files for the canonical source repository
 */

namespace Kodekit\Component\Files;

use Kodekit\Library;

/**
 * Files Model
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
 */
class ModelFiles extends ModelNodes
{
    protected function _actionFetch(Library\ModelContext $context)
    {
        $state = $context->state;

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

        $this->_count = count($files);

        if (substr( $state->sort, 0, 1 ) == '-') {
            $files = array_reverse($files);
        }

        $files = array_slice($files, $state->offset, $state->limit ? $state->limit : $this->_count);

        $data = array();
        foreach ($files as $file)
        {
            $data[] = array(
                'container' => $state->container,
                'folder'    => $state->folder,
                'name'      => $file
            );
        }

        $identifier         = $this->getIdentifier()->toArray();
        $identifier['path'] = array('model', 'entity');

        return $this->getObject($identifier, array('data' => $data));
    }

    protected function _actionCount(Library\ModelContext $context)
    {
        if (!isset($this->_count)) {
            $this->fetch();
        }

        return $this->_count;
    }

    public function iteratorMap($path)
    {
        return basename($path);
    }

    public function iteratorFilter($path)
    {
        $filename  = basename($path);
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if ($this->getState()->name)
        {
            if (!in_array($filename, (array)$this->getState()->name)) {
                return false;
            }
        }

        if ($this->getState()->types)
        {
            if ((in_array($extension, ModelEntityFile::$image_extensions) && !in_array('image', (array)$this->getState()->types))
                || (!in_array($extension, ModelEntityFile::$image_extensions) && !in_array('file', (array)$this->getState()->types))
            ) {
                return false;
            }
        }

        if ($this->getState()->search && stripos($filename, $this->getState()->search) === false) {
            return false;
        }
    }
}
