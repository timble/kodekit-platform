<?php

class ComAttachmentsViewAttachmentFile extends KViewFile
{
	public function display()
	{
		$item = $this->getModel()->getItem();
    	$state = $this->getModel()->getState();
    	
    	$container = $this->getService('com://admin/files.model.containers')
    		->slug($item->container)
    		->getItem();

        $this->path = $container->path.'/'.$item->path;
        $this->filename = $item->name;

        if (!file_exists($this->path)) {
        	throw new KViewException('File not found');
        }

        return parent::display();
	}
}