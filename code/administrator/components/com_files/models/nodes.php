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
 * Nodes Model Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files   
 */

class ComFilesModelNodes extends ComFilesModelDefault
{
	public function getList()
	{
		if (!isset($this->_list)) 
		{
			$state = $this->_state;
			$type = !empty($state->type) ? (array) $state->type : array();

			$list = KFactory::tmp('admin::com.files.database.rowset.nodes');

			if (empty($type) || in_array('folder', $type)) 
			{
				$folders = KFactory::tmp('admin::com.files.model.folders')->set($state->getData())->getList();
				foreach ($folders as $folder) {
					$list->insert($folder);
				}
			}

			if (empty($type) || (in_array('file', $type) || in_array('image', $type))) 
			{
				$files = KFactory::tmp('admin::com.files.model.files')->set($state->getData())->getList();
				foreach ($files as $file) {
					$list->insert($file);
				}
			}

			$this->_total = count($list);
			$this->_list = $list;
		}

		return parent::getList();
	}
}
