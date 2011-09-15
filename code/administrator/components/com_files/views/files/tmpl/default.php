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

<?= @template('initialize');?>

<script>
Files.sitebase = '<?= $sitebase; ?>';
Files.path = '<?= $path; ?>';
Files.baseurl = Files.sitebase + '/' + Files.path;

Files.container = <?= json_encode($state->container->toArray()); ?>;
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
	Files.app = new Files.App({
		tree: {
			theme: 'media://com_files/images/mootree.png'
		},
		types: <?= json_encode($state->types); ?>
	});

	$('files-new-folder-create').addEvent('click', function(e){
		e.stop();
		var element = this;
		var value = $('files-new-folder-input').get('value');
		if (value.length > 0) {
			var element = this;
			var folder = new Files.Folder({path: value});
			folder.add(function(response, responseText) {
				element.set('value', '');

				var el = response.item;
				var cls = Files[el.type.capitalize()];
				var row = new cls(el);
				Files.app.grid.insert(row);
				Files.app.tree.selected.insert({
					text: row.name,
					id: row.path,
					data: {
						url: '#'+row.path
					}
				});
			});
		};
	});
	$('files-new-container-create').addEvent('click', function(e){
		console.log('e');
		var element = this;
		var title = $('files-new-container-input').get('value');
		if (title.length > 0) {
			var element = this;
			var path = (Files.app.active == '/' ? '' : Files.app.active);
			path = path.replace('sites/default/', '');
			
			var element = this;
			var request = new Request.JSON({
				url: '?option=com_files&view=container&format=json',
				method: 'post',
				data: {
					'_token': Files.token,
					'path': path,
					'title': title,
					'container': Files.container.slug
				},
				onSuccess: function(response, responseText) {
					element.set('value', '');
					Files.app.containertree.addItem(response.item);
					
				},
				onFailure: function(xhr) {
					resp = JSON.decode(xhr.responseText, true);
					error = resp && resp.error ? resp.error : 'An error occurred during request';
					alert(error);
				}
			});
			request.send();
		}
	});

	$('files-new-folder-toolbar').addEvent('click', function(e) {
		e.stop();
		SqueezeBox.open($('files-new-folder-modal'), {
			handler: 'adopt',
			size: {x: 300, y: 200}
		});
	});

});
</script>


<div id="files-app" class="-koowa-box -koowa-box-flex">
	<?= @template('templates_icons'); ?>
	<?= @template('templates_details'); ?>
	
	<div id="sidebar">
		<div id="files-tree"></div>
		<?= @template('folders');?>
		
		<div id="files-containertree"></div>
	</div>
	
	<div id="files-canvas" class="-koowa-box -koowa-box-vertical -koowa-box-flex">
	    <div class="path">
			<button id="files-new-folder-toolbar" style="float: left;"><?= @text('New Folder'); ?></button>
			<button id="files-new-container-toolbar" style="float: left;"><?= @text('New Container'); ?></button>
			<button id="files-batch-delete" style="float: left;"><?= @text('Delete'); ?></button>
			
			<select id="files-layout-switcher" style="float: right">
				<option value="icons"><?= @text('Icons'); ?></option>
				<option value="details"><?= @text('Details'); ?></option>
			</select>
		</div>
		<div class="view -koowa-box-scroll -koowa-box-flex">
			<div id="files-grid"></div>
		</div>

	    <div id="files-pathway"></div>

		<?= @helper('paginator.pagination', array('limit' => $state->limit)) ?>
	
		<?= @template('uploader');?>
	</div>
	<div style="clear: both"></div>
</div>

<div style="display: block">
	<div id="files-new-folder-modal">
		<input class="inputbox" type="text" id="files-new-folder-input"  />
		<button id="files-new-folder-create"><?= @text('Create'); ?></button>
	</div>
	<div id="files-new-container-modal">
		<input class="inputbox" type="text" id="files-new-container-input"  />
		<button id="files-new-container-create"><?= @text('Create'); ?></button>
	</div>
</div>