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
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('behavior.mootools'); ?>
<?= @helper('behavior.keepalive'); ?>
<?= @helper('behavior.tooltip'); ?>

<?= @helper('behavior.modal'); ?>

<style src="media://system/css/mootree.css" />
<style src="media://com_files/css/files.css" />
<style src="media://com_files/css/files-layout-icons.css" />

<script src="media://com_files/js/delegation.js" />
<script src="media://com_files/js/ejs/ejs.js" />

<script src="media://lib_koowa/js/koowa.js" />
<script src="media://system/js/mootree.js" />

<script src="media://system/js/swiff-uploader.js" />
<script src="media://system/js/uploader.js" />

<script src="media://com_files/js/files.filesize.js" />
<script src="media://com_files/js/files.template.js" />
<script src="media://com_files/js/files.container.js" />
<script src="media://com_files/js/files.tree.js" />
<script src="media://com_files/js/files.row.js" />
<script src="media://com_files/js/files.uploader.js" />
<script src="media://com_files/js/files.app.js" />

<script>

Files.sitebase = '<?= ltrim(JURI::root(true), '/'); ?>';
Files.path = '<?= $path; ?>';
Files.baseurl = Files.sitebase +'/'+Files.path;
Files.identifier = '<?= $state->identifier->identifier; ?>';

Files.token = '<?= JUtility::getToken();?>';

window.addEvent('domready', function() {
	Files.app = new Files.App({
		tree: {
			div: 'files-tree',
			adopt: 'files-tree-html',
			theme: 'media://com_files/images/mootree.png'
		}
	});

	document.id('files-create-folder').addEvent('click', function(e) {
		e.stop();
		var request = new Request.JSON({
			url: 'index.php?option=com_files&view=folder&format=json&identifier='+Files.identifier,
			method: 'post',
			data: {
				'_token': Files.token,
				'parent': Files.app.getPath(),
				'path': document.id('foldername').getValue()
			},
			onSuccess: function(response, responseText) {
				document.id('foldername').set('value', '');

				var el = response.folder;
				var cls = Files[el.type.capitalize()];
				var row = new cls(el);
				Files.app.container.insert(row);
				Files.app.tree.selected.insert({
					text: row.name,
					id: row.path,
					data: {
						url: '#'+row.path
					}
				});
			},
			onFailure: function(xhr) {
				resp = JSON.decode(xhr.responseText, true);
				error = resp && resp.error ? resp.error : 'An error occurred during request';
				alert(error);
			}
		});
		request.send();
	});

});
</script>

<?= @template('templates_icons'); ?>
<?= @template('templates_details'); ?>

<div id="sidebar">
	<div id="files-tree"></div>
	<?= @template('folders');?>
</div>
<div id="files-canvas" class="-koowa-box -koowa-box-vertical -koowa-box-flex">
        <div class="path">
			<span id="path-active"></span>/
			<input class="inputbox" type="text" id="foldername" name="foldername"  />
			<button type="submit" id="files-create-folder"><?= @text('Create Folder'); ?></button>
			<select id="files-layout-switcher" style="float: right">
				<option value="icons"><?= @text('Icons'); ?></option>
				<option value="details"><?= @text('Details'); ?></option>
			</select>
		</div>
		<div class="view -koowa-box-scroll -koowa-box-flex">
			<div id="files-container">

			</div>
		</div>

		<? if (KFactory::get('lib.joomla.user')->authorize('com_files', 'upload')): ?>
			<?= @template('uploader');?>
		<? endif; ?>
</div>
