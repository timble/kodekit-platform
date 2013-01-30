<?php
/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Thumbnail Database Row Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
  * @package     Nooku_Components
 * @subpackage  Files
 */
class ComFilesDatabaseRowThumbnail extends KDatabaseRowTable
{
    protected $_thumbnail_size;

	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->setThumbnailSize(KConfig::unbox($config->thumbnail_size));
	}

    protected function _initialize(KConfig $config)
    {
    	$size = KConfig::unbox($config->thumbnail_size);
    	
		if (empty($size)) {
			$config->thumbnail_size = array('x' => 200, 'y' => 150);
		}

        parent::_initialize($config);
    }

    public function generateThumbnail()
    {
		@ini_set('memory_limit', '256M');

    	$source = $this->source;
    	if ($source && !$source->isNew())
		{
			//Load the phpthumb library
		    require_once JPATH_LIBRARIES.'/phpthumb/phpthumb.php';

		    //Create the thumb
		    $image = PhpThumbFactory::create($source->fullpath)
		        ->setOptions(array('jpegQuality' => 50));

			if ($this->_thumbnail_size['x'] && $this->_thumbnail_size['y'])
            {
				// Resize then crop to the provided resolution.
				$image->adaptiveResize($this->_thumbnail_size['x'], $this->_thumbnail_size['y']);
			}
            else
            {
				$width = isset($this->_thumbnail_size['x'])?$this->_thumbnail_size['x']:0;
				$height = isset($this->_thumbnail_size['y'])?$this->_thumbnail_size['y']:0;
				// PhpThumb will calculate the missing side while preserving the aspect ratio.
				$image->resize($width, $height);
			}

		    ob_start();
		    echo $image->getImageAsString();
		    $str = ob_get_clean();
		    $str = sprintf('data:%s;base64,%s', $source->mimetype, base64_encode($str));

	    	return $str;
		}

		return false;
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