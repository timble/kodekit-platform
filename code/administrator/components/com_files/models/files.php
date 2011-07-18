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
 * Files Model Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files   
 */

jimport('joomla.filesystem.folder');

class ComFilesModelFiles extends ComFilesModelDefault
{
	public function getItem()
	{
		if (!isset($this->_item)) 
		{
			$this->_item	= KFactory::tmp('admin::com.files.database.row.file', array(
				'data' => array(
					'basepath' => $this->_state->basepath,
					'path' => $this->_state->path
				)));
		}

		return parent::getItem();
	}

	public function getList()
	{
		if (!isset($this->_list)) 
		{
			$state = $this->_state;
			if (!$state->basepath) {
				throw new KModelException('Basepath is not a valid folder');
			}

			$basepath = $state->basepath;
			$path = $basepath;

			if (!empty($state->folder) && $state->folder != '/') {
				$path .= '/'.ltrim($state->folder, '/');
			}

			if (!is_dir($path)) {
				throw new KModelException('Basepath is not a valid folder');
			}

			$name = $state->path ? $state->path : null;
			if (is_string($name)) 
			{
				$files[] = $name;
			}
			else if (is_array($name)) 
			{
				$files = array();
				foreach ($name as $n) {
					$files[] = $n;
				}
			}
			else 
			{
				$filter = '.';
				$type = (array) $state->type;
				if (in_array('image', $type)) 
				{
					$filter = '(?:';
					$filter .= implode('|', ComFilesDatabaseRowFile::$image_extensions);
					$filter .= ')$';
				}
				$files = JFolder::files($path, $filter, false, true, array('.svn', '.htaccess', '.git', 'CVS', 'index.html', '.DS_Store', 'Thumbs.db', 'Desktop.ini'));

				foreach ($files as &$file) 
				{
					$file = str_replace('\\', '/', $file);
					$file = str_replace($basepath.'/', '', $file);
				}
				
				unset($file);
			}

			$search = $state->search;

			if ($search) {
				foreach ($files as $i => $file) {
					if (stripos($file, $search) === false) {
						unset($files[$i]);
					}
				}
			}

			$this->_total = count($files);

			$files = array_slice($files, $state->offset, $state->limit ? $state->limit : $this->_total);

			if (strtolower($this->_state->direction) == 'desc') {
				$files = array_reverse($files);
			}

			$data = array();
			foreach ($files as $file) 
			{
				$data[] = array(
					'basepath' => $basepath,
					'path' => $file
				);
			}

			$this->_list = KFactory::tmp('admin::com.files.database.rowset.files', array(
				'data' => $data
			));
		}

		return parent::getList();
	}
}