<?php
/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
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
Files.token = '<?= $token; ?>';

window.addEvent('domready', function() {
	var config = <?= json_encode($state->config); ?>,
		options = {
			state: {
				defaults: {
					limit: <?= (int) $state->limit; ?>,
					offset: <?= (int) $state->offset; ?>
				}
			},
			editor: <?= json_encode($state->editor); ?>,
			tree: {
				theme: 'media://com_files/images/mootree.png'
			},
			types: <?= json_encode($state->types); ?>,
			container: <?= json_encode($state->container ? $state->container->slug : null); ?>
		};
	options = $extend(options, config);

	Files.app = new Files.Compact.App(options);

	$('files-new-folder-create').addEvent('click', function(e){
		e.stop();
		var element = $('files-new-folder-input');
		var value = element.get('value');
		if (value.length > 0) {
			var folder = new Files.Folder({name: value, folder: Files.app.getPath()});
			folder.add(function(response, responseText) {
				element.set('value', '');
				$('files-new-folder-create').removeClass('valid').setProperty('disabled', 'disabled');
				var el = response.item;
				var cls = Files[el.type.capitalize()];
				var row = new cls(el);

				Files.app.tree.selected.insert({
					text: row.name,
					id: row.path,
					data: {
						path: row.path,
						url: '#'+row.path,
						type: 'folder'
					}
				});
				Files.app.tree.selected.toggle(false, true);
			});
		};
	});
	var validate = function(){
		if(this.value.trim()) {
			$('files-new-folder-create').addClass('valid').removeProperty('disabled');
		} else {
			$('files-new-folder-create').removeClass('valid').setProperty('disabled', 'disabled');
		}
	};
	$('files-new-folder-input').addEvent('change', validate);
	if(window.addEventListener) {
		$('files-new-folder-input').addEventListener('input', validate);
	} else {
		$('files-new-folder-input').addEvent('keyup', validate);
	}
});
</script>

<?= @template('com://admin/files.view.files.templates_compact');?>

<div id="files-compact">
	<?=	@helper('tabs.startPane', array('id' => 'pane_insert')); ?>
	<?= @helper('tabs.startPanel', array('title' => 'Insert')); ?>
		<div id="insert">
			<div id="files-tree-container" style="float: left">
				<div id="files-tree"></div>

				<div id="files-new-folder-modal" style="margin-top: 16px">
					<form>
						<input class="inputbox" type="text" id="files-new-folder-input" placeholder="<?= @text('New Folder...'); ?>" />
						<button id="files-new-folder-create" disabled><?= @text('Create'); ?></button>
					</form>
				</div>
			</div>

			<div id="files-grid" style="float: left"></div>
			<div id="details" style="float: left;">
				<div id="files-preview"></div>
			</div>
			<div class="clear" style="clear: both"></div>
		</div>
	<?= @helper('tabs.endPanel'); ?>
	<?= @helper('tabs.startPanel', array('title' => @text('Upload'))); ?>

		<?= @template('com://admin/files.view.files.uploader'); ?>

	<?= @helper('tabs.endPanel'); ?>
	<?= @helper('tabs.endPane'); ?>
</div>

<script>
/* Modal fixes that applies when this view is loaded within an iframe in a modal view */
window.addEvent('domready', function(){
	if(window.parent && window.parent != window && window.parent.SqueezeBox) {
		var modal = window.parent.SqueezeBox, heightfix = modal.size.y;

		document.id('files-compact').getParents().setStyles({padding: 0, margin: 0, overflow: 'hidden'});
	}
});
</script>