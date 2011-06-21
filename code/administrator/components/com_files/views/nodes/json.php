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
 * Nodes Json View Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files   
 */

class ComFilesViewNodesJson extends KViewJson
{
    public function display()
    {
		$list = $this->getModel()->getList();

		$result = array();
		foreach ($list as $row) {
			$array = $row->getData();

			$name = $row->getIdentifier()->name;
			$type = $name == 'file' && $row->isImage() ? 'image' : $name;

			// common properties
			$array['type'] = $type;
			$array['name'] = $row->name;

			if ($name == 'file') {
				$array['extension'] = $row->extension;
				$array['size'] = $row->size;
				$array['icons'] = $row->icons;
				
				if ($type == 'image') {
					$array['thumbnail'] = $row->thumbnail;
					$array['width'] = $row->width;
					$array['height'] = $row->height;
				}
			}

			$result[] = $array;
		}
    	$this->output = json_encode($result);

    	return $this->output;
    }
}