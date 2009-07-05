<?php

// no direct access
defined('_JEXEC') or die('Restricted access');


$status = new JObject();

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * KOOWA SYSTEM PLUGIN
 * ---------------------------------------------------------------------------------------------
 * **********************************************************************************************/

// Set the installation path
$element =& $this->manifest->getElementByPath('koowa_plugin/files');
if (is_a($element, 'JSimpleXMLElement') && count($element->children())) {
	$files =& $element->children();
	foreach ($files as $file) {
		if ($file->attributes('plugin')) {
			$pname = $file->attributes('plugin');
			break;
		}
	}
}
$group = 'system';
if (!empty ($pname) && !empty($group)) {
	$this->parent->setPath('extension_root', JPATH_ROOT.DS.'plugins'.DS.$group);
} else {
	$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('No plugin file specified'));
	return false;
}

/**
 * ---------------------------------------------------------------------------------------------
 * Filesystem Processing Section
 * ---------------------------------------------------------------------------------------------
 */

// If the plugin directory does not exist, lets create it
$created = false;
if (!file_exists($this->parent->getPath('extension_root'))) {
	if (!$created = JFolder::create($this->parent->getPath('extension_root'))) {
		$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('Failed to create directory').': "'.$this->parent->getPath('extension_root').'"');
		return false;
	}
}

/*
 * If we created the plugin directory and will want to remove it if we
 * have to roll back the installation, lets add it to the installation
 * step stack
 */
if ($created) {
	$this->parent->pushStep(array ('type' => 'folder', 'path' => $this->parent->getPath('extension_root')));
}

// Copy all necessary files
if ($this->parent->parseFiles($element, -1) === false) {
	// Install failed, roll back changes
	$this->parent->abort();
	return false;
}

/**
 * ---------------------------------------------------------------------------------------------
 * Database Processing Section
 * ---------------------------------------------------------------------------------------------
 */
$db = &JFactory::getDBO();

// Check to see if a plugin by the same name is already installed
$query = 'SELECT `id`' .
		' FROM `#__plugins`' .
		' WHERE folder = '.$db->Quote($group) .
		' AND element = '.$db->Quote($pname);
$db->setQuery($query);
if (!$db->Query()) {
	// Install failed, roll back changes
	$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
	return false;
}
$id = $db->loadResult();

// Was there a plugin already installed with the same name?
if ($id) {

	if (!$this->parent->getOverwrite())
	{
		// Install failed, roll back changes
		$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.JText::_('Plugin').' "'.$pname.'" '.JText::_('already exists!'));
		return false;
	}

} else {
	$row =& JTable::getInstance('plugin');
	$row->name = 'Nooku Framework (Codename Koowa) NOT FOR PRODUCTION USE';
	$row->ordering = 1;
	$row->folder = $group;
	$row->iscore = 0;
	$row->access = 0;
	$row->client_id = 0;
	$row->element = $pname;
	$row->published = 1;
	$row->params = '';

	if (!$row->store()) {
		// Install failed, roll back changes
		$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
		return false;
	}
}

$status->set('koowa_plugin', true);



/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * OUTPUT TO SCREEN
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
?>
<h1>Business Enterprise Employee Repository (B.E.E.R.)</h1>
<script>$$('table.adminform')[0].getElementsByTagName('tr')[0].setStyle('display', 'none');</script>
<table class="adminlist">
	<thead>
		<tr>
			<th class="title"><?php echo JText::_('Task'); ?></th>
			<th width="60%"><?php echo JText::_('Status'); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	</tfoot>
	<tbody>

        <tr class="row1">
            <td class="key"><?php echo JText::_('Nooku Framework System Plugin'); ?></td>
            <td><?php echo ($status->get('koowa_plugin')) ? '<strong>'.JText::_('Installed').'</strong>' : '<em>'.JText::_('NOT Installed').'</em>'; ?></td>
        </tr>
		<tr class="row1">
			<td class="key"><?php echo JText::_('PHP Version'); ?></td>
			<td>
				<?php echo version_compare(phpversion(), '5.2', '>=')
					? '<strong>'.JText::_('OK').'</strong> - '.phpversion()
					: '<em>'.JText::_('You need at least PHP v5.2 to use B.E.E.R. You are using: ').phpversion().'</em>'; ?>
			</td>
		</tr>
		<tr class="row0">
			<td class="key"><?php echo JText::_('MySQL Version'); ?></td>
			<td>
				<?php echo version_compare($db->getVersion(), '5.0.45', '>=')
				? '<strong>'.JText::_('OK').'</strong> - '.$db->getVersion()
				: '<em>'.JText::_('You need at least MySQL v5.0.45 to use B.E.E.R. You are using: ').$db->getVersion().'</em>'; ?>
			</td>
		</tr>
	</tbody>
</table>
