<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Folders Element Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Server
 * @subpackage  Files
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
		    $options[] = array('text' => JText::_('Root Folder'), 'value' => '');
		}
		
		foreach ($tree as $folder) {
			$this->_addFolder($folder, $options);
		}

		return  Library\ObjectManager::getInstance()->getObject('com:files.template.helper.select')->optionlist(array(
			'name' => $el_name,
			'options' => $options,
			'showroot' => false,
			'selected' => $value
		));
	}

	protected function _addFolder($folder, &$options)
	{
		$padded    = str_repeat('&nbsp;', 2*(count(explode('/', $folder->path)))).$folder->name;
		$options[] = array('text' => $padded, 'value' => $folder->path);

		if ($folder->hasChildren())
        {
			foreach ($folder->getChildren() as $child) {
				$this->_addFolder($child, $options);
			}
		}

	}
}
