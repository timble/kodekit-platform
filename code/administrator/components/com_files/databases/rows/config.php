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
 * Config Database Row Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesDatabaseRowConfig extends KDatabaseRowAbstract
{
	/**
	 * An array of fields to convert to array in __get
	 */
	protected $_comma_separated = array();

	public function __construct($config = array())
	{
		parent::__construct($config);

		if (!empty($config->auto_load)) {
			$this->load();
		}

		if (!empty($config->comma_separated)) {
			$this->_comma_separated = $config->comma_separated;
		}
	}

	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'auto_load' => true,
			'comma_separated' => array(
				'upload_extensions',
				'image_extensions',
				'ignore_extensions',
				'upload_mime',
				'upload_mime_illegal'
			)
		));

		parent::_initialize($config);
	}

	public function load()
	{
		$params = JComponentHelper::getComponent('com_files')->params;

		$registry = new JRegistry();
		$registry->loadIni($params);

		$this->setData($registry->toArray());
		
		return $this;
	}

	public function __get($column)
	{
		if (in_array($column, $this->_comma_separated->toArray())) 
		{
			if (isset($this->_data[$column]) && is_string($this->_data[$column])) 
			{
				$values = array();
				if (!empty($this->_data[$column])) {
					$values = explode(',', $this->_data[$column]);
				}
				
				$this->_data[$column] = $values;
			}
			else return array();
		}

		return parent::__get($column);
	}
}