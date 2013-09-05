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
 * Abstract Local Adapter
 *
 * @author   Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
abstract class AdapterLocalAbstract extends Library\Object
{
	/**
	 * Path to the node
	 */
	protected $_path = null;

	/**
	 * A pointer for the FileInfo object
	 */
	protected $_handle = null;

	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);

		$this->setPath($config->path);
	}

	protected function _initialize(Library\ObjectConfig $config)
	{
		$config->append(array(
			'path' => ''
		));

		parent::_initialize($config);
	}

	public function setPath($path)
	{
		$path = $this->normalize($path);

		$this->_path = $path;
		$this->_encoded = $this->encodePath($path);

		$this->_pathinfo = new \SplFileInfo($path);
		$this->_handle   = new \SplFileInfo($this->_encoded);

		unset($this->_metadata);

		return $this;
	}

	public function encodePath($path)
	{
		$parts = explode('/', $path);
		$prepend = '';

        // Either C:/ or ~/
		if (!empty($parts[0])) {
			$prepend = array_shift($parts).'/';
		}

		$parts = array_map(array($this, 'encode'), $parts);

		return $prepend.implode('/', $parts);
	}

	public function getName()
	{
		return $this->normalize($this->_pathinfo->getBasename());
	}

	public function getPath()
	{
		return $this->normalize($this->_pathinfo->getPathname());
	}

	public function getDirname()
	{
		return $this->normalize(dirname($this->_pathinfo->getPathname()));
	}

	public function getRealPath()
	{
		return $this->_encoded;
	}

	public function normalize($string)
	{
		return str_replace('\\', '/', $string);
	}

	public function encode($string)
	{
		$string = rawurlencode($string);

		return str_replace('%20', ' ', $string);
	}

	public function decode($string)
	{
		$string = rawurldecode($string);

		return str_replace(' ', '%20', $string);
	}
}