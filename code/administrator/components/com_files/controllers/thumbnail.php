<?php

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
       
        $results = array();
        foreach ($list as $item) {
        	$key = $item['filename'];
        	$results[$key] = $item;
        }
        ksort($results);
	}
}