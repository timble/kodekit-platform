<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * File Html View
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Component\Files
 */
class FilesViewFileHtml extends Library\ViewFile
{
    public function render()
    {
    	$state = $this->getModel()->getState();
    	$file  = $this->getModel()->getRow();

        $this->path = $file->fullpath;
        $this->filename = $file->name;
        $this->mimetype = $file->mimetype ? $file->mimetype : 'application/octet-stream';
        if ($file->isImage() || $file->extension === 'pdf') {
        	$this->disposition = 'inline';
        }

        if (!file_exists($this->path)) {
        	throw new Library\ViewException(JText::_('File not found'));
        }

        return parent::render();
    }
}