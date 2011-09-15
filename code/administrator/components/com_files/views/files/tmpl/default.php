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

<script src="media://com_files/js/files.app.js" />

<script>
Files.sitebase = '<?= trim(JURI::root(), '/'); ?>';
Files.path = '<?= $path; ?>';
Files.baseurl = Files.sitebase + '/' + Files.path;

Files.container = '<?= $state->container->id; ?>';
Files.container_title = '<?= $state->container->title; ?>';
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

	/*
	 * This is too dirty. Doesn't really work either after the second change event
	 */
	var switchLayoutEvt = function(layout) {
		grid_div = 'files-paginator-container';
		if (layout == 'details') {
			grid_div += '-details';
		} 
		document.id(grid_div).adopt(document.id('files-paginator'));
	};
	Files.app.grid.addEvent('switchLayout', switchLayoutEvt);
	Files.app.addEvent('afterSelect', function() {
		switchLayoutEvt(Files.Template.layout);
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

});
</script>


<div id="files-app" class="-koowa-box -koowa-box-flex">
	<?= @template('templates_icons'); ?>
	<?= @template('templates_details'); ?>
	
	<div id="sidebar">
		<div id="files-tree"></div>
		<?= @template('folders');?>
	</div>
	
	<div id="files-canvas" class="-koowa-box -koowa-box-vertical -koowa-box-flex">
	    <div class="path">
		    <span id="files-pathway"></span>
		    
		    <span id="files-new-folder">
			<input class="inputbox" type="text" id="files-new-folder-input" placeholder="<?= @text('New Folder...'); ?>"  />
			</span>
			
			<button id="files-batch-delete" style="float: right; margin-left: 30px;" disabled="disabled"><?= @text('Delete'); ?></button>
			
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