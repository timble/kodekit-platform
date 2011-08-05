<?php
/**
 * @version     $Id: default.php 1829 2011-06-21 01:59:15Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Configurations Model Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */
class ComFilesModelConfigs extends ComDefaultModelDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_state->insert('identifier', 'identifier', null);
	}

	public function getItem()
	{
		if (!isset($this->_item))
		{
			$this->_item = KFactory::get('admin::com.files.database.row.config');
			$identifier = KFactory::get('admin::com.files.model.paths')->identifier((string)$this->_state->identifier)->getItem();

			$this->_item->identifier = $identifier;
			$this->_item->setData(json_decode($identifier->parameters, true));
		}

		return parent::getItem();
	}
}