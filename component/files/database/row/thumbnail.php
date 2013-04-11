<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Thumbnail Database Row
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class DatabaseRowThumbnail extends Library\DatabaseRowTable
{
    protected $_thumbnail_size;

	public function __construct(Library\Config $config)
	{
		parent::__construct($config);

        spl_autoload_register(array($this, '__autoload'));

		$this->setThumbnailSize(Library\Config::unbox($config->thumbnail_size));
	}

    protected function _initialize(Library\Config $config)
    {
    	$size = Library\Config::unbox($config->thumbnail_size);
    	
		if (empty($size)) {
			$config->thumbnail_size = array('x' => 200, 'y' => 150);
		}

        parent::_initialize($config);
    }

    /**
     * Autoloader for Imagine library
     *
     * @param $className
     *
     * @return bool
     */
    private function __autoload($className)
    {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

        $fileName = JPATH_VENDOR.'/imagine/lib/'.$fileName;

        if (file_exists($fileName)) {
            require $fileName;
            return true;
        }

        return false;
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
            $x = isset($this->_thumbnail_size['x']) ? $this->_thumbnail_size['x'] : 0;
            $y = isset($this->_thumbnail_size['y']) ? $this->_thumbnail_size['y'] : 0;

            if (!$x && !$y) {
                return false;
            }

            $imagine = new \Imagine\Gd\Imagine();
            $image   = $imagine->open($source->fullpath);

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

            $thumbnail = $image->thumbnail($size, \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
            $string    = sprintf('data:image/png;base64,%s', base64_encode((string)$thumbnail));

            return $string;
        }
        catch (Exception $e) {
            return false;
        }
    }

	public function save()
	{
		if ($source = $this->source)
		{
			if (!$source->isNew())
			{
				$str = $this->generateThumbnail();

		    	$this->setData(array(
			    	'files_container_id' => $source->container->id,
					'folder'			 => $source->folder,
					'filename'           => $source->name,
					'thumbnail'          => $str
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

    public function getThumbnailSize()
    {
        return $this->_thumbnail_size;
    }

    /**
     * @param array $size An array with x and y properties
     */
    public function setThumbnailSize(array $size)
    {
        $this->_thumbnail_size = $size;
    }
}