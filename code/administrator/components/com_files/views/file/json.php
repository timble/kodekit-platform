<?php

class ComFilesViewFileJson extends KViewJson
{
    public function display()
    {
		$row = $this->getModel()->getItem();

		$result = new stdclass;
		$result->status = $row->getStatus() !== KDatabase::STATUS_FAILED && $row->path;

		if ($result->status === false) {
			$result->error = $row->getStatusMessage();
		}
		else {
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

    	$this->output = json_encode($result);

    	return $this->output;
    }
}