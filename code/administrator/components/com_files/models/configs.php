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

		$this->_state->insert('container', 'identifier', null);
	}

	public function getItem()
	{
		if (!isset($this->_item))
		{
			$this->_item = KFactory::get('admin::com.files.database.row.config');
			$container = KFactory::get('admin::com.files.model.containers')->id((string)$this->_state->container)->getItem();

			$this->_item->container = $container;
			$this->_item->setData(json_decode($container->parameters, true));
		}

		return parent::getItem();
	}
}