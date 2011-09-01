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
 * File Size Filter Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesFilterFileSize extends KFilterAbstract
{
	protected $_walk = false;

	protected $_config;

	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_config = $config;
	}

	protected function _initialize(KConfig $config)
	{
		$component_config = KFactory::get('com://admin/files.database.row.config');

		$config->append(array(
			'maximum_size' => $component_config->upload_maxsize
		));

		parent::_initialize($config);
	}

	protected function _validate($context)
	{
		$config = $this->_config;

		if ($config->maximum_size)
		{
			$row = $context->caller;
			$size = $row->contents ? strlen($row->contents) : filesize($row->file);

			if ($size > $config->maximum_size) {
				$context->setError(JText::_('WARNFILETOOLARGE'));
				return false;
			}
		}
	}

	protected function _sanitize($value)
	{
		return false;
	}
}
