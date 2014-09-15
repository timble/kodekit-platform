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
 * Thumbnail Model Entity
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ModelEntityThumbnail extends Library\ModelEntityRow
{
    /**
     * Either an array with x and y dimensions or a scalar to be used as the ratio
     *
     * @var array|int
     */
    protected $_thumbnail_size;

	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);

		$this->setThumbnailSize(Library\ObjectConfig::unbox($config->thumbnail_size));
	}

    protected function _initialize(Library\ObjectConfig $config)
    {
    	$size = Library\ObjectConfig::unbox($config->thumbnail_size);
    	
		if (empty($size)) {
			$config->thumbnail_size = array('x' => 200, 'y' => 150);
		}

        parent::_initialize($config);
    }

    public function getThumbnailSize()
    {
        return $this->_thumbnail_size;
    }

    /**
     * @param  array|int|float $size An array with x and y properties
     * @return $this
     */
    public function setThumbnailSize($size)
    {
        $this->_thumbnail_size = $size;
        return $this;
    }

    public function cropThumbnail()
    {
        @ini_set('memory_limit', '256M');

        $source = $this->source;

        if (!$source || $source->isNew()) {
            return false;
        }

        try
        {
            $imagine = new \Imagine\Gd\Imagine();
            $image   = $imagine->open($source->fullpath);

            $start = new \Imagine\Image\Point($this->x1, $this->y1);
            $size  = new \Imagine\Image\Box($this->x2 - $this->x1, $this->y2 - $this->y1);

            return $image->crop($start, $size);
        }
        catch (\Exception $e) {
            return false;
        }
    }

    public function generateThumbnail()
    {
		@ini_set('memory_limit', '256M');

    	$source = $this->source;

        if (!$source || $source->isNew()) {
            return false;
        }

        try
        {
            $imagine = new \Imagine\Gd\Imagine();
            $image   = $imagine->open($source->fullpath);

            if (isset($this->x1) && isset($this->y1)) {
                $this->cropThumbnail($image);
            }

            $x = isset($this->_thumbnail_size['x']) ? $this->_thumbnail_size['x'] : 0;
            $y = isset($this->_thumbnail_size['y']) ? $this->_thumbnail_size['y'] : 0;

            // Find the biggest possible thumbnail from the given ratio (e.g 1.33)
            if (!empty($this->_thumbnail_size) && is_scalar($this->_thumbnail_size))
            {
                $image_size = $image->getSize();
                $ratio  = $this->_thumbnail_size;
                $width  = $image_size->getWidth();
                $height = $image_size->getHeight();

                if ($width > $height)
                {
                    $x = min($height*$ratio, $width);
                    $y = min($height, 1/$ratio*$x);
                }
                else
                {
                    $x = $width;
                    $y = 1/$ratio*$width;
                }
            }

            if ($x && $y) {
                $size = new \Imagine\Image\Box($x, $y);
            }
            else
            {
                $image_size = $image->getSize();
                $larger     = max($image_size->getWidth(), $image_size->getHeight());
                $scale      = max($x, $y);

                $size       = $image_size->scale(1/($larger/$scale));
            }

            return $image->thumbnail($size, \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
        }
        catch (\Exception $e) {
            return false;
        }
    }

	public function save()
	{
		if ($source = $this->source)
		{
			if (!$source->isNew())
			{
				$string = (string) $this->generateThumbnail();
                $string = sprintf('data:image/png;base64,%s', base64_encode($string));

		    	$this->setProperties(array(
			    	'files_container_id' => $source->getContainer()->id,
					'folder'			 => $source->folder,
					'filename'           => $source->name,
					'thumbnail'          => $string
			    ));

			}
			else return false;
		}

		return parent::save();
	}

    public function toArray()
    {
        $data = parent::toArray();

        unset($data['_thumbnail_size']);
        unset($data['source']);

        return $data;
    }
}