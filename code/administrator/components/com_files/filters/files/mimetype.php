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
 * File Mimetype Filter Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files   
 */

class ComFilesFilterFileMimetype extends KFilterFilename
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
		$component_config = $this->getService('com://admin/files.database.row.config');

		$config->append(array(
			'check_mime' => $component_config->check_mime,
			'allowed_mimetypes' => array_map('strtolower', $component_config->upload_mime)
		));

		parent::_initialize($config);
	}

	protected function _validate($context)
	{
		$config = $this->_config;
		$row = $context->caller;

		if (is_uploaded_file($row->file)) 
		{
			if ($row->isImage()) 
			{
				if (getimagesize($row->file) === false) {
					$context->setError(JText::_('WARNINVALIDIMG'));
					return false;
				}
			}
			else 
			{
				$mime = $this->getService('com://admin/files.database.row.file')->setData(array('path' => $row->file))->mimetype;

				if ($config->check_mime && $mime && !in_array($mime, $config->allowed_mimetypes->toArray())) 
				{
					$context->setError(JText::_('WARNINVALIDMIME'));
					return false;
				}
			}
		}
	}

	protected function _sanitize($value)
	{
		return false;
	}
}