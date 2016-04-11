<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

/**
 * Folders Element
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Kodekit\Component\Files
 */
class JElementFolders extends JElement
{
	public $_name = 'Folders';

	function fetchElement($name, $value, $param = null, $group = null)
	{
		$el_name   = $group ? $group.'['.$name.']' : $name;
		$show_root = (bool) $param->attributes()->show_root;

        $translator = Kodekit::getObject('translator');

		$tree =  Kodekit::getObject('com:files.controller.folder')
			->container('files-files')
			->tree(1)
			->limit(0)
			->browse();

		$options = array();

		if ($show_root) {
		    $options[] = array('label' => $translator('Root Folder'), 'value' => '');
		}

		foreach ($tree as $folder) {
			$this->_addFolder($folder, $options);
		}

        $template = Kodekit::getObject('com:pages.view.page')->getTemplate();
		return  Kodekit::getObject('com:files.template.helper.select', array('template' => $template))->optionlist(array(
			'name'    => $el_name,
			'options' => $options,
			'showroot' => false,
			'selected' => $value
		));
	}

	protected function _addFolder($folder, &$options)
	{
		$padded    = str_repeat('&nbsp;', 2*(count(explode('/', $folder->path)))).$folder->name;
		$options[] = array('label' => $padded, 'value' => $folder->path);

		if ($folder->hasChildren())
        {
			foreach ($folder->getChildren() as $child) {
				$this->_addFolder($child, $options);
			}
		}
	}
}
