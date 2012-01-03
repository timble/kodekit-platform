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
 * Container Database Row Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesDatabaseRowContainer extends KDatabaseRowDefault
{
	public $adapter = 'local';
	/**
	 * A reference to the container configuration
	 * 
	 * @var ComFilesDatabaseRowConfig
	 * 
	 */
	protected $_parameters;
	
	public function __get($column)
	{
		if ($column == 'path' && !empty($this->_data['path']))
		{
			$result = $this->_data['path'];
			// Prepend with site root if it is a relative path
			if ($this->adapter === 'local' && !preg_match('#^(?:[a-z]\:|~*/)#i', $result)) {
				$result = JPATH_FILES.'/'.$result;
			}

			$result = rtrim(str_replace('\\', '/', $result), '\\');
			return $result;
		}
		else if ($column == 'relative_path') {
			return $this->getRelativePath();
		}
		else if ($column == 'path_value') {
			return $this->_data['path'];
		}
		else if ($column == 'parameters' && !is_object($this->_data['parameters'])) {
			return $this->getParameters();
		}

		return parent::__get($column);
	}

	public function getRelativePath()
	{
		$path = $this->path;
		$root = str_replace('\\', '/', JPATH_ROOT);
		return str_replace($root.'/', '', $path);
	}

	public function getParameters()
	{
		if (empty($this->_parameters)) {
			$this->_parameters = $this->getService('com://admin/files.database.row.config')
				->setData(json_decode($this->_data['parameters'], true));	
		}

		return $this->_parameters;
	}
	
	public function toArray()
	{
		$data = parent::toArray();

		$data['path'] = $this->path_value;
		$data['parameters'] = $this->parameters->toArray();
		$data['relative_path'] = $this->getRelativePath();
		
		return $data;
	}
	
	public function getData($modified = false)
	{
		$data = parent::getData($modified);
		
		if (isset($data['parameters'])) {
			$data['parameters'] = $this->parameters->getData();	
		}
		
		return $data;
	}
	
	public function getAdapter($type, array $config = array())
	{
		return $this->getService('com://admin/files.adapter.'.$this->adapter.'.'.$type, $config);
	}
}