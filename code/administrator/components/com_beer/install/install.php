<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * Developed for Brian Teeman's Developer Showdown, using Nooku Framework
 * @version		$Id$
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

// move the Nooku Framework plugin to it's location
$admin_path = 'administrator'.DS.'components'.DS.'com_beer'.DS.'koowa';
$plugins_path = 'plugins'.DS.'system';
JFile::move($admin_path.DS.$plugins_path.DS.'koowa.xml',  $plugins_path.DS.'koowa.xml', JPATH_ROOT);
JFile::move($admin_path.DS.$plugins_path.DS.'koowa.php',  $plugins_path.DS.'koowa.php', JPATH_ROOT);
JFolder::move($admin_path.DS.$plugins_path.DS.'koowa', $plugins_path.DS.'koowa', JPATH_ROOT);
JFolder::move($admin_path.DS.'media'.DS.'plg_koowa', 'media'.DS.'plg_koowa', JPATH_ROOT);

// Move the search plugin
$admin_path = 'administrator'.DS.'components'.DS.'com_beer'.DS.'search';
$plugins_path = 'plugins'.DS.'search';
JFile::move($admin_path.DS.$plugins_path.DS.'beer.xml',  $plugins_path.DS.'beer.xml', JPATH_ROOT);
JFile::move($admin_path.DS.$plugins_path.DS.'beer.php',  $plugins_path.DS.'beer.php', JPATH_ROOT);

$status = new JObject();

// Insert in database
$row = JTable::getInstance('plugin');
$row->name = 'Nooku Framework (Codename Koowa) NOT FOR PRODUCTION USE';
$row->ordering = 1;
$row->folder = 'system';
$row->iscore = 0;
$row->access = 0;
$row->client_id = 0;
$row->element = 'koowa';
$row->published = 1;
$row->params = '';
if (!$row->store()) {
	// Install failed, roll back changes
	$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
	return false;
}
$status->set('koowa_plugin', true);

$row = JTable::getInstance('plugin');
$row->name = 'Search - B.E.E.R.';
$row->ordering = 1;
$row->folder = 'search';
$row->iscore = 0;
$row->access = 0;
$row->client_id = 0;
$row->element = 'beer';
$row->published = 1;
$row->params = '';
if (!$row->store()) {
	// Install failed, roll back changes
	$this->parent->abort(JText::_('Plugin').' '.JText::_('Install').': '.$db->stderr(true));
	return false;
}
$status->set('search_plugin', true);

// Output status
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
		<tr class="row0">
            <td class="key"><?php echo JText::_('Search Plugin'); ?></td>
            <td><?php echo ($status->get('search_plugin')) ? '<strong>'.JText::_('Installed').'</strong>' : '<em>'.JText::_('NOT Installed').'</em>'; ?></td>
        </tr>
        <tr class="row1">
            <td class="key"><?php echo JText::_('Nooku Framework System Plugin'); ?></td>
            <td><?php echo ($status->get('koowa_plugin')) ? '<strong>'.JText::_('Installed').'</strong>' : '<em>'.JText::_('NOT Installed').'</em>'; ?></td>
        </tr>
		<tr class="row0">
			<td class="key"><?php echo JText::_('PHP Version'); ?></td>
			<td>
				<?php echo version_compare(phpversion(), '5.2', '>=')
					? '<strong>'.JText::_('OK').'</strong> - '.phpversion()
					: '<em>'.JText::_('You need at least PHP v5.2 to use B.E.E.R. You are using: ').phpversion().'</em>'; ?>
			</td>
		</tr>
		<tr class="row1">
			<td class="key"><?php echo JText::_('MySQL Version'); ?></td>
			<td>
				<?php echo version_compare($db->getVersion(), '5.0.45', '>=')
				? '<strong>'.JText::_('OK').'</strong> - '.$db->getVersion()
				: '<em>'.JText::_('You need at least MySQL v5.0.45 to use B.E.E.R. You are using: ').$db->getVersion().'</em>'; ?>
			</td>
		</tr>
	</tbody>
</table>
