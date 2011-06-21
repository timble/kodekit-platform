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
 * File Json View Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files   
 */

class ComFilesViewFileJson extends KViewJson
{
    public function display()
    {
		$row = $this->getModel()->getItem();

		$result = new stdclass;
		$result->status = $row->getStatus() !== KDatabase::STATUS_FAILED && $row->path;

		if ($result->status !== false) 
		{
	        $file = $row->getData();
			
			$file['name'] = $row->name;
			$file['type'] = $row->isImage() ? 'image' : 'file';
			$file['extension'] = $row->extension;
			$file['size'] = $row->size;
			$file['icons'] = $row->icons;
			
			if ($row->isImage()) {
				$file['thumbnail'] = $row->thumbnail;
				$file['width'] = $row->width;
				$file['height'] = $row->height;
			}
			
			$result->file = $file;
		}
		else $result->error = $row->getStatusMessage();
		
    	$this->output = json_encode($result);

    	return $this->output;
    }
}