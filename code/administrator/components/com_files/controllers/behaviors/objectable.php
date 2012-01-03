<?php

class ComFilesControllerBehaviorObjectable extends KControllerBehaviorAbstract
{
	protected function _beforeBrowse(KCommandContext $context)
	{
		if ($this->getMixer()->getIdentifier()->name === 'node') {
			$state = $this->getModel()->getState();
			$array = $state->getData();
			$array['files_container_id'] = $state->container->id;

			$rowset = $this->getService('com://admin/files.model.objects')->set($array)->getList();
			foreach ($rowset as $row) {
				$row->name ='canims.jpg';
				$row->metadata = $row->metadata ? json_decode($row->metadata): false;
			}
			$context->result = $rowset;
			return false;
		}
	}
	
	protected function _afterAdd(KCommandContext $context)
	{
		$result = $context->result;
		if ($result->getStatus() === KDatabase::STATUS_CREATED) {
			$array = array(
				'files_container_id' => $result->container->id,
				'folder' => $result->folder,
				'name' => $result->name
			);
			$row = $this->getService('com://admin/files.model.objects')->set($array)->getItem();
			
			$row->setData($result->getData());
			$row->type = $result->getIdentifier()->name;
			$row->files_container_id = $result->container->id;
			$row->metadata = $result->metadata ? json_encode($result->metadata) : '';
			
			if ($result->isImage() && $result->container->parameters->thumbnails) 
			{
				$thumb = $this->getService('com://admin/files.model.thumbnails')
					->source($result)
					->getItem();
	
				$row->thumbnail = $thumb->generateThumbnail();
			}
			
			$row->save();
		}
		
		return true;
	}
	
	protected function _afterDelete(KCommandContext $context)
	{
		$result = $context->result;
		if ($result->getStatus() === KDatabase::STATUS_DELETED) 
		{
			$array = array(
				'files_container_id' => $result->container->id,
				'folder' => $result->folder,
				'name' => $result->name
			);
			$row = $this->getService('com://admin/files.model.objects')->set($array)->getItem();
			if (!$row->isNew()) 
			{
				$row->delete();
				if ($result->isImage()) 
				{
					$thumb = $this->getService('com://admin/files.model.thumbnails')
						->source($result)
						->getItem();
		
					$result = $thumb->delete();
		
				}
			}
		}
		
		return true;
	}
}