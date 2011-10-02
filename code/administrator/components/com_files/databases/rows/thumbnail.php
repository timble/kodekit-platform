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
 * Thumbnail Database Row Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */
class ComFilesDatabaseRowThumbnail extends KDatabaseRowDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

        $this->_thumbnail_size = $config->thumbnail_size;
	}

    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'thumbnail_size' => 60
        ));

        parent::_initialize($config);
    }

	public function save()
	{
		if ($source = $this->source) 
		{
			if (is_file($source->fullpath) && $source->isImage()) 
			{
				//Load the library
			    $this->getService('koowa:loader')->loadIdentifier('com://admin/files.helper.phpthumb.phpthumb');
			
			    //Creat the thumb
			    $image = PhpThumbFactory::create($source->fullpath)
				    ->setOptions(array('jpegQuality' => 50))
				    ->adaptiveResize($this->_thumbnail_size, $this->_thumbnail_size);

			    ob_start();
			        echo $image->getImageAsString();
			    $str = ob_get_clean();
			    $str = sprintf('data:%s;base64,%s', $source->mimetype, base64_encode($str));

		    	$this->setData(array(
			    	'files_container_id' => $source->container->id,
					'folder'			 => '/'.$source->relative_folder,
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

        return $data;
    }
}