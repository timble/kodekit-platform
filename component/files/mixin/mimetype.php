<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Mimetype Mixin Class
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class MixinMimetype extends Library\Object
{
	/**
	 * Used as a way to continue on the chain when the method is not available.
	 */
	const NOT_AVAILABLE = -1;

	/**
	 * Adapters to use for mimetype detection
	 *
	 * @var array
	 */
	protected $_adapters = array();

	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);

		if (isset($config->adapters)) {
			$this->_adapters = Library\ObjectConfig::unbox($config->adapters);
		}
	}

	protected function _initialize(Library\ObjectConfig $config)
	{
		if (empty($config->adapters)) {
			$config->adapters = array('image', 'finfo');
		}

		parent::_initialize($config);
	}

	public function getMimetype($path)
	{
		$mimetype = false;

		if (!file_exists($path)) {
			return $mimetype;
		}

		foreach ($this->_adapters as $i => $adapter)
		{
			$function = '_detect'.ucfirst($adapter);
			$return = $this->$function($path);

			if (!empty($return) && $return !== MixinMimetype::NOT_AVAILABLE)
            {
				$mimetype = $return;
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

	protected function _detectImage($path)
	{
		if (in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), DatabaseRowFile::$image_extensions)
			&& $info = getimagesize($path)) {
			return $info['mime'];
		}

		return MixinMimetype::NOT_AVAILABLE;
	}

	protected function _detectFinfo($path)
	{
		if (!class_exists('finfo')) {
			return MixinMimetype::NOT_AVAILABLE;
		}

		// PHP updated libmagic to v5 in 5.3.11 which broke the old mimetype formats
		// Use the system wide magic file for these versions
		$database = version_compare(phpversion(), '5.3.11', '>=') ? null : dirname(__FILE__).'/mimetypes/magic';
		$finfo    = new \finfo(FILEINFO_MIME, $database);
		
		if (empty($finfo)) {
		    return MixinMimetype::NOT_AVAILABLE;
		}
		
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
			return MixinMimetype::NOT_AVAILABLE;
		}

		return mime_content_type($path);
	}
}