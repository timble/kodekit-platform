<?php
/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * File Uploadble Filter Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */

class ComFilesFilterFileUploadable extends KFilterAbstract
{
	protected $_walk = false;

	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->addFilter($this->getService('com://admin/files.filter.file.name'), KCommand::PRIORITY_HIGH);

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
