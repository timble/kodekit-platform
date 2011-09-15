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

	$('files-new-folder-input').addEvent('keydown', function(e){
		if (e.key == 'enter' && this.get('value').length > 0) {
			e.stop();
			
			var element = this;
			var folder = new Files.Folder({path: this.get('value')});
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

	$('files-new-container-input').addEvent('keydown', function(e){
		if (e.key == 'enter' && this.get('value').length > 0) {
			e.stop();
			var title = this.get('value');
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
		    <span id="files-pathway"></span>
		    
		    <span id="files-new-folder">
			<input class="inputbox" type="text" id="files-new-folder-input" placeholder="<?= @text('New Folder...'); ?>"  />
			</span>
			
			<button id="files-batch-delete" style="float: right; margin-left: 30px;"><?= @text('Delete'); ?></button>
			
			<input class="inputbox" type="text" id="files-new-container-input" style="float: right; margin-left: 0;" placeholder="<?= @text('New Container...'); ?>" />
			
			<select id="files-layout-switcher" style="float: right">
				<option value="icons"><?= @text('Icons'); ?></option>
				<option value="details"><?= @text('Details'); ?></option>
			</select>
		</div>
		<div class="view -koowa-box-scroll -koowa-box-flex">
			<div id="files-grid">
	
			</div>
		</div>

		<?= @helper('paginator.pagination', array('limit' => $state->limit)) ?>
	
		<?= @template('uploader');?>
	</div>
	<div style="clear: both"></div>
</div>