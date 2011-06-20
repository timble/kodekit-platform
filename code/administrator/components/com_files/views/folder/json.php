<?php

class ComFilesViewFolderJson extends KViewJson
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
			$result->folder = $row->getData();
			$result->folder['type'] = 'folder';
			$result->folder['name'] = $row->name;
		}

    	$this->output = json_encode($result);

    	return $this->output;
    }
}