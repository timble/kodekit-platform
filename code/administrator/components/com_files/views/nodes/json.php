<?php

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