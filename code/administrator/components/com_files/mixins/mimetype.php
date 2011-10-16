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
 * Mimetype Mixin Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesMixinMimetype extends KObject
{
	/**
	 * Used as a way to continue on the chain when the method is not available.
	 * 
	 */
	const NOT_AVAILABLE = -1;
	
	/**
	 * Adapters to use for mimetype detection
	 *
	 * @var array
	 */
	protected $_adapters = array();

	public function __construct(KConfig $config = null)
	{
		parent::__construct($config);

		if (isset($config->adapters)) {
			$this->_adapters = $config->adapters;
		}
	}

	protected function _initialize(KConfig $config)
	{
		if (empty($config->adapters)) {
			$config->adapters = array('finfo');
		}
		elseif (is_string($config->adapters)) {
			$config->adapters = array($config->adapters);
		}

		parent::_initialize($config);
	}

	public function getMimetype($path)
	{
		$mimetype = false;
		foreach ($this->_adapters as $i => $adapter)
		{
			$function = '_detect'.ucfirst($adapter);
			$mimetype = $this->$function($path);
			if (!empty($mimetype) && $mimetype !== ComFilesMixinMimetype::NOT_AVAILABLE) {
				break;
			}
		}

		// strip charset from text files
		if (!empty($mimetype) && strpos($mimetype, ';')) {
			$mimetype = substr($mimetype, 0, strpos($mimetype, ';'));
		}

		// special case: empty text files
		if ($mimetype == 'application/x-empty') {
			$mimetype = 'text/plain';
		}

		return $mimetype;
	}

	protected function _detectFinfo($path)
	{
		if (!class_exists('finfo')) {
			return ComFilesMixinMimetype::NOT_AVAILABLE;
		}

		$finfo = new finfo(FILEINFO_MIME, dirname(__FILE__).'/mimetypes/magic');
		$mimetype = $finfo->file($path);

		return $mimetype;
	}

	/**
	 * Not used by default since it can't use our magic.mime file and cannot be reliable.
	 * It's also deprecated by PHP in favor of fileinfo extension used above.
	 */
	protected function _detectMime_content_type($path)
	{
		if (!function_exists('mime_content_type')) {
			return ComFilesMixinMimetype::NOT_AVAILABLE;
		}

		return mime_content_type($path);
	}
}