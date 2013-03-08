<?php

use Nooku\Framework;

class ComAttachmentsViewAttachmentFile extends Framework\ViewFile
{
	public function render()
	{
		$item = $this->getModel()->getRow();
    	$state = $this->getModel()->getState();
    	
    	$container = $this->getService('com://admin/files.model.containers')
    		->slug($item->container)
    		->getRow();

        $this->path = $container->path.'/'.$item->path;
        $this->filename = $item->name;

        if (!file_exists($this->path)) {
        	throw new Framework\ViewException('File not found');
        }

        return parent::render();
	}
}