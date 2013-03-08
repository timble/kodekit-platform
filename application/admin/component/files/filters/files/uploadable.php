<?php
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * File Uploadble Filter Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */

class ComFilesFilterFileUploadable extends Framework\FilterAbstract
{
	protected $_walk = false;

	public function __construct(Framework\Config $config)
	{
		parent::__construct($config);

		$this->addFilter($this->getService('com://admin/files.filter.file.name'), Framework\Command::PRIORITY_HIGH);

		$this->addFilter($this->getService('com://admin/files.filter.file.extension'));
		$this->addFilter($this->getService('com://admin/files.filter.file.mimetype'));
		$this->addFilter($this->getService('com://admin/files.filter.file.size'));
	}

	protected function _validate($context)
	{

	}

	protected function _sanitize($context)
	{

	}
}
