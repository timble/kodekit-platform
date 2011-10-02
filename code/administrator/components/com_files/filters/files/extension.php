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
 * File Extension Filter Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesFilterFileExtension extends KFilterFilename
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
		$component_config = $this->getService('com://admin/files.model.configs')->getItem();

		$allowed = array_map('strtolower', $component_config->upload_extensions);
		$ignored = array_map('strtolower', $component_config->ignore_extensions);

		$config->append(array(
			'allowed' => $allowed,
			'ignored' => $ignored
		));

		parent::_initialize($config);
	}

	protected function _validate($context)
	{
		$config = $this->_config;
		$value = $context->caller->extension;

		if (empty($value) || (!in_array($value, $config->ignored->toArray()) && !in_array($value, $config->allowed->toArray()))) {
			$context->setError(JText::_('WARNFILETYPE'));
			return false;
		}
	}

	protected function _sanitize($value)
	{
		return false;
	}
}