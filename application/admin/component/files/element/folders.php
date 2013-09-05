<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Folders Element
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Component\Files
 */
class JElementFolders extends JElement
{
	public $_name = 'Folders';

	function fetchElement($name, $value, &$node = null, $control_name = null, $show_root = true)
	{
		$el_name = $control_name ? $control_name.'['.$name.']' : $name;
		$show_root = $node->attributes('show_root');

		$tree =  Library\ObjectManager::getInstance()->getObject('com:files.controller.folder')
			->container('files-files')
			->tree(1)
			->limit(0)
			->browse();

		$options = array();
		
		if ($show_root) {
		    $options[] = array('label' => JText::_('Root Folder'), 'value' => '');
		}
		
		foreach ($tree as $folder) {
			$this->_addFolder($folder, $options);
		}

		return  Library\ObjectManager::getInstance()->getObject('com:files.template.helper.select')->optionlist(array(
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
