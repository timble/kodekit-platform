<?php

class ComFilesCommandValidatorFolder extends KCommand
{
	protected function _databaseBeforeDelete($context)
	{
	}

	protected function _databaseBeforeSave($context)
	{
		$row = $context->caller;

		$row->path = KFactory::tmp('admin::com.files.filter.folder.name')->sanitize($row->path);

		return KFilter::factory('admin::com.files.filter.folder.uploadable')->validate($context);
	}
}