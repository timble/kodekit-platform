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
 * Thumbnails Json View Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */
class ComFilesViewThumbnailsJson extends ComFilesViewJson
{
    protected function _getList(KModelAbstract $model)
    {
    	// Save state data for later
        $state_data = $model->getState()->getData();

        $nodes = KFactory::tmp('admin::com.files.model.nodes')->set($state_data)->getList();

        $needed  = array();
        foreach ($nodes as $row)
        {
        	if ($row->isImage()) {
        		$needed[] = $row->name;	
        	}
        }

		$model->reset();
		$model->set('files', $needed);
		$list  = array_values($model->getList()->toArray());

    	$found = array();
        foreach ($list as $row) {
        	$found[] = $row['filename'];
        }

        if ($found !== $needed) 
        {
        	$new = array();
        	foreach ($nodes as $row) 
        	{
        		if ($row->isImage() && !in_array($row->name, $found)) 
        		{
	        		$result = $row->saveThumbnail(null);
	        		if ($result) {
	        			$new[] = $row->name;
	        		}
        		}
        	}
        	if (count($new))
        	{
				$model->reset();
				$model->set('files', $new);
				$list  = array_merge($list, array_values($model->getList()->toArray()));
        	}
        }
       
        $results = array();
        foreach ($list as $item) {
        	$key = $item['filename'];
        	$results[$key] = $item;
        }
        ksort($results);

        $output = new stdclass;
        $output->total  = count($list);
        $output->limit  = $state_data['limit'];
        $output->offset = $state_data['offset'];
        $output->items  = $results;

        return $output;
    }
}