<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Attachments;

use Nooku\Library;

/**
 * Attachment Database Row
 *
 * @author  Steven Rombauts <https://nooku.assembla.com/profile/stevenrombauts>
 * @package Nooku\Component\Attachments
 */
class DatabaseRowAttachment extends Library\DatabaseRowTable
{
	public function save()
	{
		$return = parent::save();

		if ($return && $this->row && $this->table)
        {
			$relation = $this->getObject('com:attachments.database.row.relation');
			$relation->attachments_attachment_id = $this->id;
			$relation->table = $this->table;
			$relation->row = $this->row;

			if(!$relation->load()) {
				$relation->save();
			}
		}

        // Save the thumbnail if the attachment is an image
        if ($this->file->isImage())
        {
            $thumbnail = $this->getObject('com:files.database.row.thumbnail');
            $thumbnail->source = $this->file;

            if (!file_exists($this->thumbnail_fullpath))
            {
                $thumbnail->setThumbnailSize(4/3)
                    ->generateThumbnail()
                    ->save($this->thumbnail_fullpath);
            }

            if (isset($this->x1) && isset($this->x2))
            {
                // Cropping existing thumbnail
                $thumbnail->setData(array(
                    'source' => $this->file,
                    'x1' => $this->x1,
                    'x2' => $this->x2,
                    'y1' => $this->y1,
                    'y2' => $this->y2
                ))
                    ->cropThumbnail()
                    ->save($this->thumbnail_fullpath);
            }
        }

        return $return;
	}
	
	public function delete()
	{
		$return = parent::delete();
			
		if ($return)
        {
            $request = $this->getObject('lib:controller.request', array(
                'query' => array(
                    'container' => $this->container,
                    'name' => array(
                        $this->path,
                        $this->thumbnail
                    )
                )
            ));

            try {
                $this->getObject('com:files.controller.file', array(
                    'request' => $request
                ))->delete();
            }
            catch (Library\ControllerExceptionNotFound $e) {}

			$this->getObject('com:attachments.database.table.relations')
				->select(array('attachments_attachment_id' => $this->id))
                ->delete();
		}

		return $return;
	}
	
	public function __get($name)
	{
	    if($name == 'relation' && !isset($this->relation))
	    {
	        $this->relation = $this->getObject('com:attachments.database.table.relations')
	            ->select(array('attachments_attachment_id' => $this->id), Library\Database::FETCH_ROW);
	    }
        
        if($name == 'file' && !isset($this->file))
	    {
	    	$this->file = $this->getObject('com:files.model.files')
	    					->container($this->container)
	    					->name($this->path)
	    					->getRow();
	    }

	    if($name == 'thumbnail' && !isset($this->thumbnail) && $this->file)
	    {
            $path  = pathinfo($this->path);
            $path['dirname'] = $path['dirname'] === '.' ? '' : $path['dirname'].'/';

            $thumbnail = $path['dirname'].$path['filename'].'_thumb.'.$path['extension'];

            $this->thumbnail = $thumbnail;
	    }

        if($name == 'thumbnail_fullpath' && $this->file) {
            $this->thumbnail_fullpath = dirname($this->file->fullpath).'/'.$this->thumbnail;
        }
	    
	    return parent::__get($name);
	}

    public function toArray()
    {
        $data = parent::toArray();

        $data['thumbnail'] = $this->thumbnail;

        return $data;
    }
}