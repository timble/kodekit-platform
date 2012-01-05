<?php

abstract class ComFilesAdapterLocalAbstract extends KObject
{
	/**
	 * Path to the node 
	 */
	protected $_path = null;

	/**
	 * A pointer for the FileInfo object
	 */
	protected $_handle = null;

	public function __construct(KConfig $config = null)
	{
		parent::__construct($config);

		$this->setPath($config->path);
	}

	protected function _initialize(KConfig $config)
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

		$this->_pathinfo = new SplFileInfo($path);
		$this->_handle = new SplFileInfo($this->_encoded);
		
		unset($this->_metadata);

		return $this;
	}
	
	public function encodePath($path)
	{
		$parts = explode('/', $path);
		$prepend = '';
		
		if (!empty($parts[0])) {
			// Either C:/ or ~/
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