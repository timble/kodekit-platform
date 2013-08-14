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
use Nooku\Component\Files;

/**
 * Thumbnail Controller Class
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ControllerThumbnail extends ControllerAbstract
{
    protected function _actionBrowse(Library\CommandContext $context)
    {
    	// Clone to make cacheable work since we change model states
        $model = clone $this->getModel();

    	// Save state data for later
        $state_data = $model->getState()->getValues();
        
        $nodes = $this->getObject('com:files.model.nodes')->setState($state_data)->getRowset();

        if (!$model->getState()->files && !$model->getState()->filename) 
        {
        	$needed  = array();
        	foreach ($nodes as $row)
        	{
        		if ($row instanceof Files\DatabaseRowFile && $row->isImage()) {
        			$needed[] = $row->name;
        		}
        	}
        } 
        else $needed = $model->getState()->files ? $model->getState()->files : $model->getState()->filename;
        
		$model->reset()
		      ->setState($state_data)
		      ->files($needed);

		$list = $model->getRowset();

    	$found = array();
        foreach ($list as $row) {
        	$found[] = $row->filename;
        }

        if (count($found) !== count($needed))
        {
        	$new = array();
        	foreach ($nodes as $row)
        	{
        		if ($row instanceof Files\DatabaseRowFile && $row->isImage() && !in_array($row->name, $found))
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
				    ->getState()->setValues($state_data)
				    ->set('files', $new);
				
				$additional = $model->getRowset();
				
				foreach ($additional as $row) {
					$list->insert($row);
				}
        	}
        }

        return $list;
    }
}