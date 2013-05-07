<?php

use Nooku\Library;

class AttachmentsViewAttachmentFile extends Library\ViewFile
{
	public function render()
	{
		$item = $this->getModel()->getRow();
    	$state = $this->getModel()->getState();
    	
    	$container = $this->getObject('com:files.model.containers')
    		->slug($item->container)
    		->getRow();

        $this->path = $container->path.'/'.$item->path;
        $this->filename = $item->name;

        if (!file_exists($this->path)) {
        	throw new Library\ViewException('File not found');
        }

        return parent::render();
	}
}