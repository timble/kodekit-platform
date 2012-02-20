<?php

class ComAttachmentsDatabaseBehaviorAttachable extends KDatabaseBehaviorAbstract
{
	public function getAttachments()
	{
		return $this->getService('com://admin/attachments.model.attachments')
				->row($this->id)
				->table($this->getTable()->getBase())
				->getList();
	}
}