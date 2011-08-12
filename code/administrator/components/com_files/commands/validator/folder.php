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
 * Folder Validator Command Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesCommandValidatorFolder extends KCommand
{
	protected function _databaseBeforeSave($context)
	{
		$row = $context->caller;

		$row->path = KFactory::tmp('admin::com.files.filter.folder.name')->sanitize($row->path);

		return KFilter::factory('admin::com.files.filter.folder.uploadable')->validate($context);
	}
}