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

class ComFilesDatabaseRowFile extends ComFilesDatabaseRowNode
{
	public static $image_extensions = array('jpg', 'jpeg', 'gif', 'png', 'tiff', 'tif', 'xbm', 'bmp');

	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->registerCallback(array('after.save'), array($this, 'saveThumbnail'));
		$this->registerCallback(array('after.delete'), array($this, 'deleteThumbnail'));
	}
	
	public function save()
	{
		$context = $this->getCommandContext();
		$context->result = false;

		$is_new = $this->isNew();

		if ($this->getCommandChain()->run('before.save', $context) !== false)
		{
			$context->result = $this->_adapter->write(!empty($this->contents) ? $this->contents : $this->file);

			$this->getCommandChain()->run('after.save', $context);
        }

		if ($context->result === false)
		{
			$this->setStatus(KDatabase::STATUS_FAILED);
			$this->setStatusMessage($context->getError());
		} else {
			$this->setStatus($is_new ? KDatabase::STATUS_CREATED : KDatabase::STATUS_UPDATED);
		}

		return $context->result;
	}

	public function __get($column)
	{
		if (in_array($column, array('size', 'extension', 'modified_date', 'mimetype'))) {
			$metadata = $this->_adapter->getMetadata();
			return $metadata ? $metadata[$column] : false;
		}
		
		if ($column == 'metadata') {
			$metadata = $this->_adapter->getMetadata();
			if ($this->isImage() && !empty($metadata))
			{
				$image = array(
					'thumbnail' => $this->thumbnail,
					'width' => $this->width,
					'height' => $this->height
				);
				$metadata['image'] = $image;
			}
			return $metadata;
		}

		if (in_array($column, array('width', 'height', 'thumbnail')) && $this->isImage()) {
			return $this->getImageSize($column);
		}

		return parent::__get($column);
	}

    public function toArray()
    {
        $data = parent::toArray();
        
        unset($data['file']);
		unset($data['contents']);
		
		$data['metadata'] = $this->metadata;

		if ($this->isImage()) {
			$data['type'] = 'image';
		}

        return $data;
    }

	public function isImage()
	{
		return in_array($this->extension, self::$image_extensions);
	}

	public function getImageSize($column)
	{
		$size = $this->_adapter->getImageSize();
		
		if ($size === false) {
			return false;
		}

		list($width, $height) = $size;

		switch ($column)
		{
			case 'width':
				return $width;
			case 'height':
				return $height;
			case 'thumbnail':
				if ($width < 200 && $height < 200) {
					// go down to default case
				}
				else {
					$higher = $width > $height ? $width : $height;
					$ratio = 200 / $higher;
					return array_map('round', array('width' => $ratio*$width, 'height' => $ratio*$height));
				}
			default:
				return array('width' => $width, 'height' => $height);
		}
	}

	public function saveThumbnail(KCommandContext $context = null)
	{
		$result = null;
		if ($this->isImage() && $this->container->getParameters()->thumbnails) 
		{
			$thumb = $this->getService('com://admin/files.model.thumbnails')
				->source($this)
				->getItem();

			$result = $thumb->save();
		}

		return $result;
	}

	public function deleteThumbnail(KCommandContext $context = null)
	{
		$result = null;
		if ($this->isImage()) 
		{
			$thumb = $this->getService('com://admin/files.model.thumbnails')
				->source($this)
				->getItem();

			$result = $thumb->delete();

		}

		return $result;
	}
}