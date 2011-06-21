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
 * Folder Json View Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesViewFolderJson extends KViewJson
{
    public function display()
    {
		$row = $this->getModel()->getItem();

		$result = new stdclass;
		$result->status = $row->getStatus() !== KDatabase::STATUS_FAILED && $row->path;

		if ($result->status !== false)
		{
	        $result->folder = $row->getData();
			$result->folder['type'] = 'folder';
			$result->folder['name'] = $row->name;
		}
		else $result->error = $row->getStatusMessage();

    	$this->output = json_encode($result);

    	return $this->output;
    }
}