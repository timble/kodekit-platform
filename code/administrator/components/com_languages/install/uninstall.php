<?php
/**
 * @version     $Id: uninstall.php 1121 2010-05-26 16:53:49Z johan $
 * @category   	Nooku
 * @package     Nooku_Administrator
 * @subpackage  Install
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.nooku.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

$status = new JObject();

// Get the database connection object and make sure we are using the unwrapped version
$db = &$this->parent->getDBO();
if (isset($db->dbo) && is_object($db->dbo)) {
	$mydb = &$db->dbo;
	$db = $mydb;
}

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * LANGUAGE SELECT ADMINISTRATOR MODULE
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/

$this->parent->setPath('extension_root', JPATH_ADMINISTRATOR.DS.'modules'.DS.'mod_language_select');

// Get the package manifest objecct
$this->parent->setPath('source', $this->parent->getPath('extension_root'));

// Lets delete all the module copies for the type we are uninstalling
$query = 'SELECT `id`' .
		' FROM `#__modules`' .
		' WHERE module = '.$db->Quote('mod_language_select') .
		' AND client_id = '.(int)1;
$db->setQuery($query);
$modules = $db->loadResultArray();

// Do we have any module copies?
if (count($modules)) {
	JArrayHelper::toInteger($modules);
	$modID = implode(',', $modules);
	$query = 'DELETE' .
			' FROM #__modules_menu' .
			' WHERE moduleid IN ('.$modID.')';
	$db->setQuery($query);
	if (!$db->query()) {
		JError::raiseWarning(100, JText::_('Module').' '.JText::_('Uninstall').': '.$db->stderr(true));
		$retval = false;
	}
}

// Delete the module in the #__modules table
$query = 'DELETE FROM #__modules WHERE module = '.$db->Quote('mod_language_select').' AND client_id = 1';
$db->setQuery($query);
if (!$db->query()) {
	JError::raiseWarning(100, JText::_('Module').' '.JText::_('Uninstall').': '.$db->stderr(true));
	$retval = false;
}

// Remove the installation folder
if (!JFolder::delete($this->parent->getPath('extension_root'))) {
	// JFolder should raise an error
	$retval = false;
}
$status->set('mod_language_select_admin', true);

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * LANGUAGE SELECT SITE MODULE
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/

$this->parent->setPath('extension_root', JPATH_SITE.DS.'modules'.DS.'mod_language_select');

// Get the package manifest objecct
$this->parent->setPath('source', $this->parent->getPath('extension_root'));

// Lets delete all the module copies for the type we are uninstalling
$query = 'SELECT `id`' .
		' FROM `#__modules`' .
		' WHERE module = '.$db->Quote('mod_language_select') .
		' AND client_id = '.(int)0;
$db->setQuery($query);
$modules = $db->loadResultArray();

// Do we have any module copies?
if (count($modules)) {
	JArrayHelper::toInteger($modules);
	$modID = implode(',', $modules);
	$query = 'DELETE' .
			' FROM #__modules_menu' .
			' WHERE moduleid IN ('.$modID.')';
	$db->setQuery($query);
	if (!$db->query()) {
		JError::raiseWarning(100, JText::_('Module').' '.JText::_('Uninstall').': '.$db->stderr(true));
		$retval = false;
	}
}

// Delete the module in the #__modules table
$query = 'DELETE FROM #__modules WHERE module = '.$db->Quote('mod_language_select').' AND client_id = 0';
$db->setQuery($query);
if (!$db->query()) {
	JError::raiseWarning(100, JText::_('Module').' '.JText::_('Uninstall').': '.$db->stderr(true));
	$retval = false;
}

// Remove the installation folder
if (!JFolder::delete($this->parent->getPath('extension_root'))) {
	// JFolder should raise an error
	$retval = false;
}
$status->set('mod_language_select_site', true);

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * NOOKU SYSTEM PLUGIN
 * ---------------------------------------------------------------------------------------------
 * **********************************************************************************************/

// Set the plugin root path
$this->parent->setPath('extension_root', JPATH_ROOT.DS.'plugins'.DS.'system');

// Delete the module in the #__modules table
$query = 'DELETE FROM #__plugins WHERE element = '.$db->Quote('nooku').' AND folder = '.$db->Quote('system');
$db->setQuery($query);
if (!$db->query()) {
	JError::raiseWarning(100, JText::_('Plugin').' '.JText::_('Uninstall').': '.$db->stderr(true));
	$retval = false;
}

// Set the installation path
$element =& $this->manifest->getElementByPath('nooku_plugin/files');
if (is_a($element, 'JSimpleXMLElement') && count($element->children())) {
	$this->parent->removeFiles($element, -1);
}

// If the folder is empty, let's delete it
$files = JFolder::files($this->parent->getPath('extension_root'));
if (!count($files)) {
	JFolder::delete($this->parent->getPath('extension_root'));
}
$status->set('nooku_plugin', true);

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * NOOKU EDITOR BUTTON PLUGIN
 * ---------------------------------------------------------------------------------------------
 * **********************************************************************************************/

// Set the plugin root path
$this->parent->setPath('extension_root', JPATH_ROOT.DS.'plugins'.DS.'editors-xtd');

// Delete the module in the #__modules table
$query = 'DELETE FROM #__plugins WHERE element = '.$db->Quote('nooku').' AND folder = '.$db->Quote('editors-xtd');
$db->setQuery($query);
if (!$db->query()) {
	JError::raiseWarning(100, JText::_('Plugin').' '.JText::_('Uninstall').': '.$db->stderr(true));
	$retval = false;
}

// Set the installation path
$element =& $this->manifest->getElementByPath('nooku_editor_plugin/files');
if (is_a($element, 'JSimpleXMLElement') && count($element->children())) {
	$this->parent->removeFiles($element, -1);
}

// If the folder is empty, let's delete it
$files = JFolder::files($this->parent->getPath('extension_root'));
if (!count($files)) {
	JFolder::delete($this->parent->getPath('extension_root'));
}
$status->set('nooku_editor_plugin', true);

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * NOOKU XML-RPC PLUGIN
 * ---------------------------------------------------------------------------------------------
 * **********************************************************************************************/

// Set the plugin root path
$this->parent->setPath('extension_root', JPATH_ROOT.DS.'plugins'.DS.'xmlrpc');

// Delete the module in the #__modules table
$query = 'DELETE FROM #__plugins WHERE element = '.$db->Quote('nooku').' AND folder = '.$db->Quote('xmlrpc');
$db->setQuery($query);
if (!$db->query()) {
	JError::raiseWarning(100, JText::_('Plugin').' '.JText::_('Uninstall').': '.$db->stderr(true));
	$retval = false;
}

// Set the installation path
$element =& $this->manifest->getElementByPath('nooku_xmlrpc_plugin/files');
if (is_a($element, 'JSimpleXMLElement') && count($element->children())) {
	$this->parent->removeFiles($element, -1);
}

// If the folder is empty, let's delete it
$files = JFolder::files($this->parent->getPath('extension_root'));
if (!count($files)) {
	JFolder::delete($this->parent->getPath('extension_root'));
}
$status->set('nooku_xmlrpc_plugin', true);

/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * SETUP DEFAULTS
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/



/***********************************************************************************************
 * ---------------------------------------------------------------------------------------------
 * OUTPUT TO SCREEN
 * ---------------------------------------------------------------------------------------------
 ***********************************************************************************************/
?>
<h1>Nooku Uninstallation</h1>

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
			<td class="key"><?php echo JText::_('Administrator Module'); ?></td>
			<td><?php echo ($status->get('mod_language_select_admin')) ? '<strong>'.JText::_('Uninstalled').'</strong>' : '<em>'.JText::_('NOT Uninstalled').'</em>'; ?></td>
		</tr>
		<tr class="row0">
			<td class="key"><?php echo JText::_('Site Module'); ?></td>
			<td><?php echo ($status->get('mod_language_select_site')) ? '<strong>'.JText::_('Uninstalled').'</strong>' : '<em>'.JText::_('NOT Uninstalled').'</em>'; ?></td>
		</tr>
		<tr class="row1">
			<td class="key"><?php echo JText::_('System Plugin'); ?></td>
			<td><?php echo ($status->get('nooku_plugin')) ? '<strong>'.JText::_('Uninstalled').'</strong>' : '<em>'.JText::_('NOT Uninstalled').'</em>'; ?></td>
		</tr>
		<tr class="row0">
			<td class="key"><?php echo JText::_('Editor Plugin'); ?></td>
			<td><?php echo ($status->get('nooku_editor_plugin')) ? '<strong>'.JText::_('Uninstalled').'</strong>' : '<em>'.JText::_('NOT Uninstalled').'</em>'; ?></td>
		</tr>
		<tr class="row1">
			<td class="key"><?php echo JText::_('XML-RPC Plugin'); ?></td>
			<td><?php echo ($status->get('nooku_xmlrpc_plugin')) ? '<strong>'.JText::_('Uninstalled').'</strong>' : '<em>'.JText::_('NOT Uninstalled').'</em>'; ?></td>
		</tr>
	</tbody>
</table>
