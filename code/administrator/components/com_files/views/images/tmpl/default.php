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
<style src="media://com_files/css/images-default.css" />

<script src="media://com_files/js/delegation.js" />
<script src="media://com_files/js/ejs/ejs.js" />

<script src="media://lib_koowa/js/koowa.js" />
<script src="media://system/js/mootree.js" />

<script src="media://system/js/swiff-uploader.js" />
<script src="media://system/js/uploader.js" />

<script src="media://com_files/js/files.js" />
<script src="media://com_files/js/files-template.js" />
<script src="media://com_files/js/files-container.js" />
<script src="media://com_files/js/files-tree.js" />
<script src="media://com_files/js/files-row.js" />
<script src="media://com_files/js/files-uploader.js" />

<script src="media://com_files/js/images.js" />

<script>

Files.sitebase = '<?= ltrim(JURI::root(true), '/'); ?>';
Files.baseurl = '<?= ltrim(JURI::root(true), '/').'/'.$path; ?>';

Files.path = '<?= $path; ?>';
Files.token = '<?= JUtility::getToken();?>';

window.addEvent('domready', function() {
	Files.app = new Files.Images.App({
		editor: '<?= $editor; ?>',
		tree: {
			div: 'folder-tree',
			adopt: 'folder-tree-html',
			theme: 'media://com_files/images/mootree.png'
		}
	});

	document.id('files-create-folder').addEvent('click', function(e) {
		e.stop();
		var request = new Request.JSON({
			url: 'index.php?option=com_files&view=folder&format=json',
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
				Files.app.tree.selected.insert({
					text: row.name,
					id: row.path,
					data: {
						url: '#!'+row.path
					}
				});
			},
			onFailure: function(xhr) {
				resp = JSON.decode(xhr.responseText, true);
				error = resp && resp.error ? resp.error : 'An error occurred during request';
				alert(error);
				document.id('foldername').set('value', '');
			}
		});
		request.send();
	});

	document.id('insert-image').addEvent('click', function(e) {
		e.stop();
		Files.app.insertImage();
		window.parent.SqueezeBox.close();
	});
	document.id('close-modal').addEvent('click', function(e) {
		e.stop();
		window.parent.SqueezeBox.close();
	});

});
</script>

<?= @toolbar(); ?>

<?= @template('templates');?>
<?= @template('folders');?>

<div id="images">
	<?=	@helper('tabs.startPane', array('id' => 'pane_insert')); ?>
	<?= @helper('tabs.startPanel', array('title' => 'Insert')); ?>
		<div id="insert" class="-koowa-box-horizontal">
			<div id="folder-tree" class="-koowa-box-flex scroll"></div>
			<div id="images-canvas" class="scroll"></div>
			<div id="details" class="-koowa-box-vertical">
				<div id="image-details" class="-koowa-box-flex"></div>
				<table class="properties">
					<tr class="hide">
						<td><label for="url"><?= @text('Image URL') ?></label></td>
						<td><input type="text" id="url" value="" /></td>
					</tr>
					<tr>
						<td><label for="alt"><?= @text('Image description') ?></label></td>
						<td><input type="text" id="alt" value="" /></td>
					</tr>
					<tr>
						<td><label for="title"><?= @text('Title') ?></label></td>
						<td><input type="text" id="title" value="" /></td>
					</tr>
					<tr>
						<td><label for="align"><?= @text('Align') ?></label></td>
						<td>
							<select size="1" id="align" title="Positioning of this image">
								<option value="" selected="selected"><?= @text('Not Set') ?></option>
								<option value="left"><?= @text('Left') ?></option>
								<option value="right"><?= @text('Right') ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td><label for="caption"><?= @text('Caption') ?></label></td>
						<td><input type="checkbox" id="caption" /></td>
					</tr>
				</table>
				<div class="buttons">
					<button type="button" id="insert-image"><?= @text('Insert') ?></button>
					<button type="button" id="close-modal"><?= @text('Cancel') ?></button>
				</div>
			</div>
		</div>
		<div class="path">
			<span id="path-active"></span>/
			<input class="inputbox" type="text" id="foldername" name="foldername"  />
			<button type="submit" id="files-create-folder"><?= @text('Create Folder'); ?></button>
		</div>
	<?= @helper('tabs.endPanel'); ?>
	<?= @helper('tabs.startPanel', array('title' => 'Upload')); ?>
	<? if (KFactory::get('lib.joomla.user')->authorize('com_files', 'upload')): ?>
		<?= @template('admin::com.files.view.files.uploader'); ?>
	<? endif; ?>
	<?= @helper('tabs.endPanel'); ?>
	<?= @helper('tabs.endPane'); ?>
</div>