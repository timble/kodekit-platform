<?php

class ComAttachmentsDatabaseRowAttachment extends KDatabaseRowDefault
{
	public function save()
	{
		$return = parent::save();
			
		if ($return && $this->row && $this->table) {
			$relation = $this->getService('com://admin/attachments.database.row.relation');
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
			
		if ($return) {
			$controller = $this->getService('com://admin/files.controller.file', array(
				'request' => array('container' => $this->container)
			));
			$controller->path($this->path)->delete();
			
			$relations = $this->getService('com://admin/attachments.database.table.relations')
				->select(array('attachments_attachment_id' => $this->id));
			$relations->delete();
		}

		return $return;
	}
	
	public function __get($name)
	{
	    if($name == 'relation' && is_null($this->relation))
	    {
	        $this->relation = $this->getService('com://admin/attachments.database.table.relations')
	            ->select(array('attachments_attachment_id' => $this->id), KDatabase::FETCH_ROW);
	    }
	    
	    return parent::__get($name);
	}
}