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

<?= @template('com://admin/files.views.files.initialize'); ?>

<script src="media://com_files/js/files.compact.js" />

<style src="media://com_files/css/files.css" />
<style src="media://com_files/css/files-compact.css" />

<script>
Files.sitebase = '<?= $sitebase; ?>';
Files.token = '<?= JUtility::getToken();?>';

Files.blank_image = 'media://com_files/images/blank.png';

Files.state = {
	limit: 0,
	offset: 0,
	setDefaults: function() {
		this.limit = <?= $state->limit; ?>;
		this.offset = <?= $state->offset; ?>;
	}
};
Files.state.setDefaults();

window.addEvent('domready', function() {
	Files.app = new Files.Compact.App({
		editor: <?= json_encode($state->editor); ?>,
		tree: {
			theme: 'media://com_files/images/mootree.png'
		},
		types: <?= json_encode($state->types); ?>,
		container: <?= json_encode($state->container ? $state->container->slug : 'files-files'); ?>
	});

	$('files-new-folder-create').addEvent('click', function(e){
		e.stop();
		var element = $('files-new-folder-input');
		var value = element.get('value');
		if (value.length > 0) {
			var folder = new Files.Folder({path: value});
			folder.add(function(response, responseText) {
				element.set('value', '');
				var el = response.item;
				var cls = Files[el.type.capitalize()];
				var row = new cls(el);

				Files.app.tree.selected.insert({
					text: row.name,
					id: row.path,
					data: {
						url: '#'+row.path
					}
				});
				Files.app.tree.selected.toggle(false, true);
			});
		};
	});	
});
</script>

<?= @template('com://admin/files.view.files.templates_compact');?>

<div id="files-compact">
	<?=	@helper('tabs.startPane', array('id' => 'pane_insert')); ?>
	<?= @helper('tabs.startPanel', array('title' => 'Insert')); ?>
		<div id="insert">
			<div id="files-tree-container" style="float: left; width: 200px">
				<div id="files-tree"></div>
			
				<div id="files-new-folder-modal" style="margin-top: 16px">
					<form>
						<input class="inputbox" type="text" id="files-new-folder-input" placeholder="<?= @text('New Folder...'); ?>" />
						<button id="files-new-folder-create"><?= @text('Create'); ?></button>
					</form>
				</div>
			</div> 

			<div id="files-grid" style="float: left; width: 200px;"></div>
			<div id="details" style="float: left;">
				<div id="files-preview"></div>
			</div>
			<div style="clear: both"></div>
		</div>
	<?= @helper('tabs.endPanel'); ?>
	<?= @helper('tabs.startPanel', array('title' => 'Upload')); ?>

		<?= @template('com://admin/files.view.files.uploader'); ?>

	<?= @helper('tabs.endPanel'); ?>
	<?= @helper('tabs.endPane'); ?>
</div>