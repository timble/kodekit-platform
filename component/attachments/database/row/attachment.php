<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Attachments;

use Nooku\Framework;

/**
 * Attachment Database Row
 *
 * @author  Steven Rombauts <https://nooku.assembla.com/profile/stevenrombauts>
 * @package Nooku\Component\Attachments
 */
class DatabaseRowAttachment extends Framework\DatabaseRowDefault
{
	public function save()
	{
		$return = parent::save();
			
		if ($return && $this->row && $this->table) {
			$relation = $this->getService('com:attachments.database.row.relation');
			$relation->attachments_attachment_id = $this->id;
			$relation->table = $this->table;
			$relation->row = $this->row;

			if(!$relation->load()) {
				$relation->save();
			}
		}
		
		return $return;
	}
	
	public function delete()
	{
		$return = parent::delete();
			
		if ($return)
        {
			$this->getService('com:files.controller.file', array(
				'request' => $this->getService('lib:controller.request', array(
                                'query' => array('container' => $this->container, 'name' => $this->path)
                            ))
			))->delete();
			
			$relations = $this->getService('com:attachments.database.table.relations')
				->select(array('attachments_attachment_id' => $this->id));
			$relations->delete();
		}

		return $return;
	}
	
	public function __get($name)
	{
	    if($name == 'relation' && !isset($this->relation))
	    {
	        $this->relation = $this->getService('com:attachments.database.table.relations')
	            ->select(array('attachments_attachment_id' => $this->id), Framework\Database::FETCH_ROW);
	    }
        
        if($name == 'file' && !isset($this->file))
	    {
	    	$this->file = $this->getService('com:files.model.files')
	    					->container($this->container)
	    					->folder($this->path)
	    					->name($this->name)
	    					->getRow();
	    }
	    
	    if($name == 'thumbnail' && !isset($this->thumbnail))
	    {
	    	$file = $this->file;
	    	
	    	if($file && $file->isImage())
	    	{
	    		$this->thumbnail = $this->getService('com:files.controller.thumbnail')
	    				->container($this->container)
	    				->filename($this->path)
	    				->read();
	    	}
	    }
	    
	    return parent::__get($name);
	}
}