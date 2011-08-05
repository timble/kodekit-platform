<?php
/**
 * @version     $Id: default.php 2201 2011-07-13 16:04:22Z ercanozkaya $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @template('admin::com.files.views.files.initialize'); ?>

<script src="media://com_files/js/files.compact.js" />

<style src="media://com_files/css/files-compact.css" />

<script>
Files.sitebase = '<?= $sitebase; ?>';
Files.path = '<?= $path; ?>';
Files.baseurl = Files.sitebase + '/' + Files.path;

Files.container = '<?= $state->container->id; ?>';
Files.token = '<?= $token; ?>';

Files.blank_image = 'media://com_files/images/blank.png';

Files.state = {
	limit: 0,
	offset: 0,
	setDefaults: function() {}
};
Files.state.setDefaults();

window.addEvent('domready', function() {
	Files.app = new Files.Compact.App({
		editor: '<?= $state->editor; ?>',
		tree: {
			theme: 'media://com_files/images/mootree.png'
		},
		types: <?= json_encode($state->types); ?>
	});

	$('files-new-folder-input').addEvent('keydown', function(e){
		if (e.key == 'enter' && this.get('value').length > 0) {
			var element = this;
			var folder = new Files.Folder({path: this.get('value')});
			folder.add(function(response, responseText) {
				element.set('value', '');

				var el = response.item;
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
			});
			e.stop();
		};
	});
});
</script>

<?= @template('admin::com.files.view.files.templates_compact');?>
<?= @template('admin::com.files.view.files.folders');?>

<div id="files-compact">
	<?=	@helper('tabs.startPane', array('id' => 'pane_insert')); ?>
	<?= @helper('tabs.startPanel', array('title' => 'Insert')); ?>
		<div id="insert" class="-koowa-box-horizontal">
			<div id="files-tree" class="-koowa-box-flex scroll" style="float: left; width: 200px"></div>
			<div id="files-container" class="scroll" style="float: left"></div>
			<div id="details" class="-koowa-box-vertical" style="float: left">
				<div id="files-preview" class="-koowa-box-flex"></div>
			</div>
			<div style="clear: both"></div>
		</div>
	    <div class="path">
		    <span id="files-pathway"></span>
		    
		    <span id="files-new-folder">
			<input class="inputbox" type="text" id="files-new-folder-input" placeholder="<?= @text('New Folder...'); ?>"  />
			</span>
		</div>	
	<?= @helper('tabs.endPanel'); ?>
	<?= @helper('tabs.startPanel', array('title' => 'Upload')); ?>

		<?= @template('admin::com.files.view.files.uploader'); ?>

	<?= @helper('tabs.endPanel'); ?>
	<?= @helper('tabs.endPane'); ?>
</div>