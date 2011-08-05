<?php
/**
 * @version     $Id: nodes.php 2150 2011-07-07 13:38:47Z ercanozkaya $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Thumbnails Model Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */
class ComFilesModelThumbnails extends ComDefaultModelDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_state
			->insert('container', 'identifier', null)
			->insert('folder', 'admin::com.files.filter.path', null)
			->insert('files', 'admin::com.files.filter.path', null)
			->insert('source', 'raw', null, true)
			;
	}

	public function getItem()
	{
		$item = parent::getItem();

		if ($item) {
			$item->source = $this->_source;
		}

		return $item;
	}

	protected function _buildQueryWhere(KDatabaseQuery $query)
    {
		if ($this->_state->source instanceof KDatabaseRowInterface) {
			$source = $this->_state->source;

			$query->where('tbl.files_container_id', '=', $source->container->id)
				->where('tbl.folder', '=', '/'.$source->relative_folder)
				->where('tbl.filename', '=', $source->name)
				;

			/*
			 * This is here so that parent method won't try to use the source row object when creating the query
			 */
			$this->_source = $source;
			$this->_state->source = null;
		}
		else if ($this->_state->folder) {
			$query->where('tbl.folder', '=', $this->_state->folder);
		}
		else if ($this->_state->files) {
			$query->where('tbl.filename', 'IN', $this->_state->files);
		}

		parent::_buildQueryWhere($query);

	}
}