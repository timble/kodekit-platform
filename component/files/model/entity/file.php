<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * File Model Entity
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ModelEntityFile extends ModelEntityNode
{
	public static $image_extensions = array('jpg', 'jpeg', 'gif', 'png', 'tiff', 'tif', 'xbm', 'bmp');

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->addBehavior('com:files.database.behavior.thumbnail');
    }

    public function save()
	{
		$context = $this->getContext();
		$context->result = false;

		$is_new = $this->isNew();

		if ($this->invokeCommand('before.save', $context) !== false)
		{
			$context->result = $this->_adapter->write(!empty($this->contents) ? $this->contents : $this->file);
            $this->invokeCommand('after.save', $context);
        }

		if ($context->result === false) {
			$this->setStatus(self::STATUS_FAILED);
		} else {
            $this->setStatus($is_new ? self::STATUS_CREATED : self::STATUS_UPDATED);
        }

		return $context->result;
	}

    public function getPropertyFilename()
    {
        return pathinfo($this->name, PATHINFO_FILENAME);
    }

    public function getPropertySize()
    {
        if($metadata = $this->_adapter->getMetadata())
        {
            if(isset($metadata['size'])) {
                return $metadata['size'];
            }
        }

        return false;
    }

    public function getPropertyExtension()
    {
        if($metadata = $this->_adapter->getMetadata())
        {
            if(isset($metadata['extension'])) {
                return $metadata['extension'];
            }
        }

        return false;
    }

    public function getPropertyModifiedDate()
    {
        if($metadata = $this->_adapter->getMetadata())
        {
            if(isset($metadata['modified_date'])) {
                return $metadata['modified_date'];
            }
        }

        return false;
    }

    public function getPropertyMimetype()
    {
        if($metadata = $this->_adapter->getMetadata())
        {
            if(isset($metadata['mimetype'])) {
                return $metadata['mimetype'];
            }
        }

        return false;
    }

    public function getPropertyWidth()
    {
        if($this->isImage())
        {
            $size = $this->_adapter->getImageSize();

            if ($size !== false) {
                return $size[0];
            }
        }

        return false;
    }

    public function getPropertyHeight()
    {
        if($this->isImage())
        {
            $size = $this->_adapter->getImageSize();

            if ($size !== false) {
                return $size[1];
            }
        }

        return false;
    }

    public function getPropertyMetadata()
    {
        $metadata = $this->_adapter->getMetadata();
        if ($this->isImage() && !empty($metadata))
        {
            $image = array(
                'width'     => $this->width,
                'height'    => $this->height
            );

            $metadata['image'] = $image;
        }

        return $metadata;
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
		return in_array(strtolower($this->extension), self::$image_extensions);
	}
}