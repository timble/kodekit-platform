<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Container Database Row
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */

class DatabaseRowContainer extends Library\DatabaseRowTable
{
	/**
	 * A reference to the container configuration
	 *
	 * @var DatabaseRowConfig
	 */
	protected $_parameters;

	public function get($property)
	{
		if ($property == 'path' && !empty($this->_data['path']))
		{
			$result = $this->_data['path'];
			// Prepend with site root if it is a relative path
			if ($this->adapter === 'local' && !preg_match('#^(?:[a-z]\:|~*/)#i', $result)) {
				$result = JPATH_FILES.'/'.$result;
			}

			$result = rtrim(str_replace('\\', '/', $result), '\\');
			return $result;
		}

        if ($property == 'relative_path') {
		    return $this->getRelativePath();
		}

        if ($property == 'path_value') {
			return $this->_data['path'];
		}

        if ($property == 'parameters' && !is_object($this->_data['parameters'])) {
			return $this->getParameters();
		}

        if ($property == 'adapter')
        {
		    if (strpos($this->_data['path'], '://') !== false) {
		        return substr($this->_data['path'], 0, strpos($this->_data['path'], '://'));
		    } else {
		        return 'local';
		    }
		}

		return parent::get($property);
	}

	public function getRelativePath()
	{
	    if ($this->adapter === 'local')
        {
	        $path = $this->path;
	        $root = str_replace('\\', '/', JPATH_ROOT);
	        return str_replace($root.'/', '', $path);
	    }
	    
	    return null;
	}

	public function getParameters()
	{
		if (empty($this->_parameters))
        {
			$this->_parameters = $this->getObject('com:files.database.row.config')
				->setProperties(json_decode($this->_data['parameters'], true));
		}

		return $this->_parameters;
	}

	public function toArray()
	{
		$data = parent::toArray();

		$data['path']           = $this->path_value;
		$data['parameters']     = $this->parameters->toArray();
		$data['relative_path']  = $this->getRelativePath();

		return $data;
	}

	public function getProperties($modified = false)
	{
		$data = parent::getProperties($modified);

		if (isset($data['parameters'])) {
			$data['parameters'] = $this->parameters->getProperties();
		}

		return $data;
	}

	public function getAdapter($type, array $config = array())
	{
	    return $this->getObject('com:files.adapter.'.$this->adapter.'.'.$type, $config);
	}
}