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
 * Folder Uploadable Filter Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */

class ComFilesFilterFolderUploadable extends Framework\FilterAbstract
{
	protected $_walk = false;

	public function __construct(Framework\Config $config)
	{
		parent::__construct($config);

		$this->addFilter($this->getService('com://admin/files.filter.folder.name'), Framework\Command::PRIORITY_HIGH);
	}

	protected function _validate($context)
	{

	}

	protected function _sanitize($context)
	{

	}
}
