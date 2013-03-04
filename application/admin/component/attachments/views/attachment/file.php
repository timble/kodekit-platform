<?php
class ComAttachmentsViewAttachmentFile extends KViewFile
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
        	throw new KViewException('File not found');
        }

        return parent::render();
	}
}