<?php
$status = new JObject();

// Get the database connection object and make sure we are using the unwrapped version
$db = &$this->parent->getDBO();
if (isset($db->dbo) && is_object($db->dbo)) {
	$mydb = &$db->dbo;
	$db = $mydb;
}

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * KOOWA SYSTEM PLUGIN
 * ---------------------------------------------------------------------------------------------
 * **********************************************************************************************/

// Set the plugin root path
$this->parent->setPath('extension_root', JPATH_ROOT.DS.'plugins'.DS.'system');

// Delete the module in the #__modules table
$query = 'DELETE FROM #__plugins WHERE element = '.$db->Quote('koowa').' AND folder = '.$db->Quote('system');
$db->setQuery($query);
if (!$db->query()) {
	JError::raiseWarning(100, JText::_('Plugin').' '.JText::_('Uninstall').': '.$db->stderr(true));
	$retval = false;
}

// Set the installation path
$element =& $this->manifest->getElementByPath('koowa_plugin/files');
if (is_a($element, 'JSimpleXMLElement') && count($element->children())) {
	$this->parent->removeFiles($element, -1);
}

// If the folder is empty, let's delete it
$files = JFolder::files($this->parent->getPath('extension_root'));
if (!count($files)) {
	JFolder::delete($this->parent->getPath('extension_root'));
}
$status->set('koowa_plugin', true);



/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * OUTPUT TO SCREEN
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
?>
<h1>B.E.E.R. Uninstallation</h1>

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
		<tr class="row0">
			<td class="key"><?php echo JText::_('Component'); ?></td>
			<td><strong><?php echo JText::_('Uninstalled'); ?></strong></td>
		</tr>
		<tr class="row1">
			<td class="key"><?php echo JText::_('System Plugin'); ?></td>
			<td><?php echo ($status->get('koowa_plugin')) ? '<strong>'.JText::_('Uninstalled').'</strong>' : '<em>'.JText::_('NOT Uninstalled').'</em>'; ?></td>
		</tr>
	</tbody>
</table>
