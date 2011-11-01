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
Files.token = '<?= $token; ?>';

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
	var config = <?= json_encode($state->config); ?>,
		options = {
			tree: {
				theme: 'media://com_files/images/mootree.png'
			},
			types: <?= json_encode($state->types); ?>,
			container: <?= json_encode($state->container ? $state->container->slug : 'files-files'); ?>
		};

	if (typeOf(config) === 'object') {
		options = $extend(options, config);
	}
	
	Files.app = new Files.App(options);

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

    Files.createModal = function(container, button){
        var modal = $(container);
        document.body.grab(modal);
        modal.setStyle('display', 'none');
    	$(button).addEvent('click', function(e) {
    		e.stop();
    		var coordinates = this.getCoordinates();
    		
    		modal.setStyles({
    		    'display': modal.getStyle('display') != 'block' ? 'block' : 'none',
    		    'top': coordinates.bottom,
    		    'left': coordinates.left
    		});
    	});
    };

    Files.createModal('files-new-folder-modal', 'files-new-folder-toolbar');

    Files.app.addEvent('afterNavigate', function(path) {
        if (path) {
	        var folder = path.split('/');
	        folder = folder[folder.length-1] || Files.container.title;
	        this.setTitle(folder);
        }
    });

    var switchers = $$('.files-layout-switcher'); 
    switchers.filter(function(el) { 
        return el.get('data-layout') == Files.Template.layout
    }).setProperty('disabled', true);

    switchers.addEvent('click', function(e) {
    	e.stop();
    	Files.app.grid.setLayout(this.get('data-layout'));
    	switchers.setProperty('disabled', false);
    	this.setProperty('disabled', true);
    });
    
});
</script>


<div id="files-app" class="-koowa-box -koowa-box-flex">
	<?= @template('templates_icons'); ?>
	<?= @template('templates_details'); ?>
	
	<div id="sidebar">
		<div id="files-tree"></div>
		
		<div id="files-containertree"></div>
	</div>
	
	<div id="files-canvas" class="-koowa-box -koowa-box-vertical -koowa-box-flex">
	    <div class="path" style="height: 24px;">
			<button id="files-new-folder-toolbar" style="float: left;"><?= @text('New Folder'); ?></button>
			<button id="files-batch-delete" style="float: left;"><?= @text('Delete'); ?></button>
			<h3 id="files-title" style="display: inline; "></h3>
			<span style="float: right">
				<button class="files-layout-switcher" data-layout="icons">Icons</button>
				<button class="files-layout-switcher" data-layout="details">Details</button>
			</span>
		</div>
		<div class="view -koowa-box-scroll -koowa-box-flex">
			<div id="files-grid"></div>
		</div>

		<?= @helper('paginator.pagination', array('limit' => $state->limit)) ?>
	
		<?= @template('uploader');?>
	</div>
	<div style="clear: both"></div>
</div>

<div style="display: block">
	<div id="files-new-folder-modal" class="files-modal">
		<input class="inputbox" type="text" id="files-new-folder-input"  />
		<button id="files-new-folder-create"><?= @text('Create'); ?></button>
	</div>
</div>