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
	public function save()
	{
		$is_new = $this->isNew();
		
		if ($is_new && $this->container) {
			$container = KFactory::get('com://admin/files.model.containers')->slug($this->container)->getItem();
			if ($container->isNew()) {
				$this->setStatus(KDatabase::STATUS_FAILED);
				$this->setStatusMessage(JText::_('Invalid container'));
			}

			$this->path = rtrim($container->path_value.$this->_data['path'], '/');
			$obj = $container->getParameters()->getData();
			unset($obj->container);
			$this->parameters = json_encode($obj);
		} 
				
		$result = parent::save();
		
		return $result;
	}

	public function __get($column)
	{
		if ($column == 'path' && !empty($this->_data['path']))
		{
			$result = $this->_data['path'];
			// Prepend with site root if it is a relative path
			if (!preg_match('#^(?:[a-z]\:|~*/)#i', $result)) {
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

		return parent::__get($column);
	}

	public function __toString()
	{
		return (string) $this->path;
	}

	public function getRelativePath()
	{
		$path = $this->path;
		$root = str_replace('\\', '/', JPATH_ROOT);
		return str_replace($root.'/', '', $path);
	}

	public function getParameters()
	{
		return KFactory::get('com://admin/files.model.configs')
			->container($this->slug)->getItem();
	}
	
	public function toArray()
	{
		$data = parent::toArray();
		
		$data['path'] = $this->path_value;
		$data['parameters'] = $this->getParameters()->toArray();
		$data['relative_path'] = $this->getRelativePath();
		
		return $data;
	}
}