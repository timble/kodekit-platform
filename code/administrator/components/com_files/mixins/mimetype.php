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

class ComFilesMixinMimetype extends KMixinAbstract
{
	/**
	 * Adapters to use for mimetype detection
	 * 
	 * @var array
	 */
	protected $_adapters = array();

	public function __construct($config = array())
	{
		parent::__construct($config);

		if (isset($config->adapters)) {
			$this->_adapters = $config->adapters;
		}
	}

	protected function _initialize(KConfig $config)
	{
		if (empty($config->adapters)) {
			$config->adapters = array('finfo', 'mime_content_type', 'mimemagic');
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
			try {
				$function = '_detect'.ucfirst($adapter);
				$mimetype = $this->$function($path);
			}
			catch (ComFilesMixinMimetypeException $e) {
				continue;
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
			throw new ComFilesMixinMimetypeException('Fileinfo extension is not found');
		}

		$finfo = new finfo(FILEINFO_MIME, dirname(__FILE__).'/mimetype/magic');
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
			throw new ComFilesMixinMimetypeException('mime_content_type function is not found');
		}

		return mime_content_type($path);
	}

	protected function _detectMimemagic($path)
	{
		$mimetype = false;

		// special case: 0 byte file
		if(strlen(file_get_contents($path)) === 0) {
			return 'application/x-empty';
		}

		$mimemagics = KFactory::tmp('admin::com.files.database.rowset.mimemagics')->getData();

		$fp = @fopen($path, 'rb');
		foreach ($mimemagics as $mime) {
			@fseek($fp, $mime[0]);
			$lookup = @fread($fp, $mime[1]);
			if ($lookup === $mime[2]) {
				$mimetype = $mime[3];
				break;
			}
		}

		return $mimetype;
	}
}

class ComFilesMixinMimetypeException extends Exception {}
