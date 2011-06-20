<?php

class ComFilesCommandValidatorFile extends KCommand
{
	protected function _databaseBeforeDelete($context)
	{
	}

	protected function _databaseBeforeSave($context)
	{
		$row = $context->caller;

		if (!is_uploaded_file($row->file)) {
			// remote file
			$file = KFactory::tmp('admin::com.files.database.row.remotefile');
			$file->setData(array('file' => $row->file));
			$file->load();
			$row->contents = $file->contents;

			if (empty($row->path)) {
				$uri = KFactory::tmp('lib.koowa.http.url', array('url' => $row->file));
	        	$path = $uri->get(KHttpUrl::PART_PATH | KHttpUrl::PART_FORMAT);
	        	if (strpos($path, '/') !== false) {
	        		$path = basename($path);
	        	}
	        	$row->path = $path;
			}
		}

		$row->path = KFactory::tmp('admin::com.files.filter.file.name')->sanitize($row->path);

		return KFilter::factory('admin::com.files.filter.file.uploadable')->validate($context);
	}
}