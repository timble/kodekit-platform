<?php
/**
 * @version     $Id: file.php 1205 2011-11-03 20:30:41Z ercanozkaya $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Thumbnail Controller Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */
class ComFilesControllerThumbnail extends ComFilesControllerDefault
{
    protected function _actionBrowse(KCommandContext $context)
    {
        $model = $this->getModel();
    	// Save state data for later
        $state_data = $model->getState()->getData();

        $nodes = $this->getService('com://admin/files.model.nodes')->set($state_data)->getList();

        $needed  = array();
        foreach ($nodes as $row)
        {
        	if ($row->isImage()) {
        		$needed[] = $row->name;	
        	}
        }

		$model->reset()
		    ->set($state_data)
		    ->set('files', $needed);
		$list  = $model->getList();

    	$found = array();
        foreach ($list as $row) {
        	$found[] = $row->filename;
        }

        if (count($found) !== count($needed)) 
        {
        	$new = array();
        	foreach ($nodes as $row) 
        	{
        		if ($row->isImage() && !in_array($row->name, $found)) 
        		{
	        		$result = $row->saveThumbnail();
	        		if ($result) {
	        			$new[] = $row->name;
	        		}
        		}
        	}
        	if (count($new))
        	{
				$model->reset()
				    ->set($state_data)
				    ->set('files', $new);
				$additional = $model->getList();
				foreach ($additional as $row) {
					$list->insert($row);
				}
        	}
        }

        return $list;
    }
}