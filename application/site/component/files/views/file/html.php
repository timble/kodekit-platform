<?php
/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

class ComFilesViewFileHtml extends Framework\ViewFile
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
        	throw new Framework\ViewException(JText::_('File not found'));
        }

        return parent::render();
    }
}