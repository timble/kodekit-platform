<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * File Validator Command Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesCommandValidatorFile extends KCommand
{
	protected function _databaseBeforeSave($context)
	{
		$row = $context->caller;

		if (!is_uploaded_file($row->file))
		{
			// remote file
			$file = KFactory::get('com://admin/files.database.row.url');
			$file->setData(array('file' => $row->file));
			$file->load();
			$row->contents = $file->contents;

			if (empty($row->path))
			{
				$uri = KFactory::get('koowa:http.url', array('url' => $row->file));
	        	$path = $uri->get(KHttpUrl::PATH | KHttpUrl::FORMAT);
	        	if (strpos($path, '/') !== false) {
	        		$path = basename($path);
	        	}

	        	$row->path = $path;
			}
		}

		$row->path = KFactory::get('com://admin/files.filter.file.name')->sanitize($row->path);

		return KFilter::factory('com://admin/files.filter.file.uploadable')->validate($context);
	}
}