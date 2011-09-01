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
 * File Database Row Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesDatabaseRowFile extends KDatabaseRowAbstract
{
	public static $image_extensions = array('jpg', 'jpeg', 'gif', 'png', 'tiff', 'tif', 'xbm', 'bmp');

	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->mixin(new KMixinCommandchain($config->append(array('mixer' => $this))));

        if ($config->validator !== false)
        {
        	if ($config->validator === true) {
        		$config->validator = 'com://admin/files.command.validator.'.$this->getIdentifier()->name;
        	}

			$this->getCommandChain()->enqueue(KFactory::get($config->validator));
        }

		$this->registerCallback(array('after.save', 'after.delete'), array($this, 'setPath'));
		$this->registerCallback(array('after.save'), array($this, 'saveThumbnail'));
		$this->registerCallback(array('after.delete'), array($this, 'deleteThumbnail'));
	}

    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'dispatch_events'   => false,
            'enable_callbacks'  => true,
        	'validator' 		=> true
        ));

        parent::_initialize($config);
    }

	public function setPath(KCommandContext $context)
	{
		if ($this->parent)
		{
			$this->path = $this->parent.'/'.$this->path;
			$this->parent = '';
		}
	}

	public function saveThumbnail(KCommandContext $context = null)
	{
		$result = null;
		if ($this->isImage()) {
			$thumb = KFactory::get('com://admin/files.model.thumbnails')
				->source($this)
				->getItem();

			$result = $thumb->save();
		}

		return $result;
	}

	public function deleteThumbnail(KCommandContext $context = null)
	{
		$result = null;
		if ($this->isImage()) {
			$thumb = KFactory::get('com://admin/files.model.thumbnails')
				->source($this)
				->getItem();

			$result = $thumb->delete();

		}

		return $result;
	}

	public function save()
	{
		$context = $this->getCommandContext();
		$context->result = false;

		if ($this->getCommandChain()->run('before.save', $context) !== false)
		{
			if (!empty($this->contents)) {
				$context->result = file_put_contents($this->fullpath, $this->contents);
			}
			else if (!empty($this->file)) {
				$context->result = move_uploaded_file($this->file, $this->fullpath);
			}

			$this->getCommandChain()->run('after.save', $context);
        }

		if ($context->result === false)
		{
			$this->setStatus(KDatabase::STATUS_FAILED);
			$this->setStatusMessage($context->getError());
		}

		return $context->result;
	}

	public function delete()
	{
		$context = $this->getCommandContext();
		$context->result = false;

		if ($this->getCommandChain()->run('before.delete', $context) !== false)
		{
        	$context->result = unlink($this->fullpath);
			$this->getCommandChain()->run('after.delete', $context);
        }

		if ($context->result === false) {
			$this->setStatus(KDatabase::STATUS_FAILED);
			$this->setStatusMessage($context->getError());
		}

		return $context->result;
	}

	public function isNew()
	{
		return (empty($this->path) || !file_exists($this->fullpath)) ? true : false;
	}

    public function toArray()
    {
        $data = parent::toArray();
        
        unset($data['file']);
        unset($data['container']);
        unset($data['_token']);
        unset($data['option']);
        unset($data['format']);

		unset($data['basepath']);

		$data['type'] = $this->isImage() ? 'image' : 'file';
		$data['name'] = $this->name;
		$data['extension'] = $this->extension;
		$data['size']      = $this->size;
		$data['icons']     = $this->icons;

		if ($this->isImage() == 'image')
		{
			$data['thumbnail'] = $this->thumbnail;
			$data['width']     = $this->width;
			$data['height']    = $this->height;
		}

        return $data;
    }

	public function __get($column)
	{
		if ($column == 'fullpath' && !isset($this->_data['fullpath'])) {
			return $this->getFullpath();
		}

		if ($column == 'extension' && !isset($this->_data['extension'])) {
			return $this->getExtension();
		}

		if ($column == 'name') {
			return basename($this->_data['path']);
		}

		if ($column == 'size' && !isset($this->_data['size'])) {
			$this->_data['size'] = $this->getSize();
		}

		if ($column == 'relative_folder') {
			$path = $this->fullpath;
			$result = dirname(str_replace($this->basepath.'/', '', $path));
			return $result === '.' ? '' : $result;
		}

		if ($column == 'mimetype' && !isset($this->_data['mimetype'])) {
			$this->_data['mimetype'] = $this->getMimeType();
		}

		if ($column == 'icons' && !isset($this->_data['icons'])) {
			return $this->getIcons();
		}

		if (in_array($column, array('width', 'height', 'thumbnail')) && $this->isImage()) {
			return $this->getImageSize($column);
		}

		return parent::__get($column);
	}

	public function __set($column, $value)
	{
		if (in_array($column, array('path', 'basepath', 'name')))
		{
			unset($this->size);
			unset($this->mimetype);
		}

		if ($column == 'name')
		{
			$path = dirname($this->_data['path']);
			$path .= '/'.$value;
			$this->_data['path'] = $path;
		}
		else if ($column == 'parent') {
			$this->_data['parent'] = trim($value, '\\/');
		}
		else parent::__set($column, $value);
	}

	public function getFullpath()
	{
		$path = rtrim($this->basepath, '/');
		if ($this->parent) {
			$path .= '/'.$this->parent;
		}

		$path .= '/'.$this->path;

		return $path;
	}

	public function getSize()
	{
		return file_exists($this->fullpath) ? filesize($this->fullpath) : (!empty($this->contents) ? strlen($this->contents) : false);
	}

	public function getMimeType()
	{
		return KFactory::get('com://admin/files.mixin.mimetype')->getMimetype($this->fullpath);
	}

	public function getExtension()
	{
		return strtolower(pathinfo($this->fullpath, PATHINFO_EXTENSION));
	}

	public function isImage()
	{
		return in_array($this->extension, self::$image_extensions);
	}

	public function getImageSize($column)
	{
		list($width, $height) = getimagesize($this->fullpath);

		switch ($column)
		{
			case 'width':
				return $width;
			case 'height':
				return $height;
			case 'thumbnail':
				if ($width < 60 && $height < 60) {
					// go down to default case
				}
				else {
					$higher = $width > $height ? $width : $height;
					$ratio = 60 / $higher;
					return array_map('round', array('width' => $ratio*$width, 'height' => $ratio*$height));
				}
			default:
				return array('width' => $width, 'height' => $height);
		}
	}

	public function getIcons()
	{
		static $path = 'media/com_files/images', $default, $icons16, $icons32;

		if (!isset($default)) {
			$default = $path.'/con_info.png';
		}
		if (!isset($icons16)) {
			$icons16 = ComFilesIteratorDirectory::getFiles(array(
            	'path' => JPATH_ROOT.'/'.$path.'/mime-icon-16',
				'filter' => array('png')
            ));
		}
		if (!isset($icons32)) {
			$icons32 = ComFilesIteratorDirectory::getFiles(array(
            	'path' => JPATH_ROOT.'/'.$path.'/mime-icon-32',
				'filter' => array('png')
            ));
		}

		$icons = array();

		$icons['16'] = in_array($this->extension.'.png', $icons16) ? $path.'/mime-icon-16/'.$this->extension.'.png' : $default;
		$icons['32'] = in_array($this->extension.'.png', $icons32) ? $path.'/mime-icon-32/'.$this->extension.'.png' : $default;

		return $icons;
	}
}