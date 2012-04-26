<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @template('initialize');?>

<script>
Files.sitebase = '<?= $sitebase; ?>';
Files.token = '<?= $token; ?>';

window.addEvent('domready', function() {
	var config = <?= json_encode($state->config); ?>,
		options = {
			state: {
				defaults: {
					limit: <?= (int) $state->limit; ?>,
					offset: <?= (int) $state->offset; ?>,
					types: <?= json_encode($state->types); ?>
				}
			},
			tree: {
				theme: 'media://com_files/images/mootree.png'
			},
			types: <?= json_encode($state->types); ?>,
			container: <?= json_encode($container ? $container->slug : null); ?>,
			thumbnails: <?= json_encode($container ? $container->parameters->thumbnails : true); ?>
		};
	options = $extend(options, config);
	
	Files.app = new Files.App(options);

	//@TODO hide the uploader in a modal, make it pretty
	var tmp = new Element('div', {style: 'display:none'}).inject(document.body);
	$('files-upload').inject(tmp);
	$('files-show-uploader').addEvent('click', function(e){
		e.stop();

		var handleClose = function(){
			$('files-upload').inject(tmp);
			SqueezeBox.removeEvent('close', handleClose);
		};
		SqueezeBox.addEvent('close', handleClose);
		SqueezeBox.open($('files-upload'), {
			handler: 'adopt',
			size: {x: 700, y: $('files-upload').measure(function(){return this.getSize().y;})}
		});
	});

	$('files-new-folder-modal').getElement('form').addEvent('submit', function(e){
		e.stop();
		var element = $('files-new-folder-input');
		var value = element.get('value');
		if (value.length > 0) {
			var folder = new Files.Folder({name: value, folder: Files.app.getPath()});
			folder.add(function(response, responseText) {
				if (response.status === false) {
					return alert(response.error);
				}
				element.set('value', '');
				$('files-new-folder-create').removeClass('valid').setProperty('disabled', 'disabled');
				var el = response.item;
				var cls = Files[el.type.capitalize()];
				var row = new cls(el);
				Files.app.grid.insert(row);
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

				SqueezeBox.close();
			});
		};
	});

    Files.createModal = function(container, button){
        var modal = $(container);
        modal.setStyle('display', 'none');
        document.body.grab(modal);
    	$(button).addEvent('click', function(e) {
    		e.stop();

    		var handleClose = function(){
					modal.setStyle('display', 'none').inject(document.body);

					SqueezeBox.removeEvent('close', handleClose);
				},
				handleOpen = function(){
					var focus = modal.getElement('input.focus');
		    		if (focus) {
		        		focus.focus();
		    		}

					SqueezeBox.removeEvent('open', handleOpen);
				},
				sizes = modal.measure(function(){return this.getSize();});

			SqueezeBox.addEvent('close', handleClose);
			SqueezeBox.addEvent('open', handleOpen);
			SqueezeBox.open(modal.setStyle('display', 'block'), {
				handler: 'adopt',
				size: {x: sizes.x, y: sizes.y}
			});

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
    };

    Files.createModal('files-new-folder-modal', 'files-new-folder-toolbar');

    var switchers = $$('.files-layout-switcher'),
    	slider = document.id('files-thumbs-size');
	
	if(slider.type != 'range' && 'Slider' in window) {
	    var container = slider.getParent('.files-layout-grid-resizer-container').addClass('fallback'),
                newSlider = new Element('div', {
                'id': slider.id+'-shim',
                'class': 'slider'
            }).grab(new Element('div', {'class': 'knob'}))
              .replaces(slider);
        document.body.adopt(slider.hide());

        // Create the new slider instance
        var instance = new Slider(newSlider, newSlider.getElement('.knob'), {
            range: [slider.get('min').toInt(), slider.get('max').toInt()]
        });

        var once = false;
        slider.addEvent('change', function(){
        	if(!once) {
        		instance.addEvent('change', function(value){
        			Files.app.grid.setIconSize(value);
                	Files.app.setDimensions.call(Files.app, true);
        		});
        		once = true;
        	}
	        instance.set(this.value);
	    });
        var slider = container;
    } else if(slider.type != 'range') {
    	document.getElement('.files-layout-grid-resizer-container').setStyle('display', 'none');
	    var slider = false;
	} else {
	    slider.addEvent('change', function(event){
	        Files.app.grid.setIconSize(this.value);
	        Files.app.setDimensions.call(Files.app, true);
	    });
	}
	
    switchers.filter(function(el) { 
        return el.get('data-layout') == Files.app.grid.layout;
    }).addClass('active');

    switchers.addEvent('click', function(e) {
    	e.stop();
    	var layout = this.get('data-layout');
    	Files.app.grid.setLayout(layout);
    	if(slider) slider.setStyle('display', layout == 'icons' ? 'block' : 'none');
    	switchers.removeClass('active');
    	this.addClass('active');
    });
    if (Files.app.grid.layout != 'icons') {
    	if(slider) slider.setStyle('display', 'none');
    }
});
</script>

<?= @template('com://admin/default.view.grid.toolbar'); ?>

<div id="files-app" class="-koowa-box -koowa-box-flex">
	<?= @template('templates_icons'); ?>
	<?= @template('templates_details'); ?>
	
	<div id="sidebar">
		<div id="files-tree"></div>
	</div>
	
	<div id="files-canvas" class="-koowa-box -koowa-box-vertical -koowa-box-flex">
	    <div class="path" style="height: 24px;">
	        <div class="files-toolbar-controls">
	        	<button id="files-show-uploader"><?= @text('Upload'); ?></button>
			    <button id="files-new-folder-toolbar"><?= @text('New Folder'); ?></button>
			    <button id="files-batch-delete"><?= @text('Delete'); ?></button>
			</div>
			<h3 id="files-title"></h3>
			<div class="files-layout-controls">
				<button class="files-layout-switcher files-layout-switcher-icons" data-layout="icons" title="<?= @text('Show files as icons'); ?>">
					<?= @text('Icons'); ?>
				</button>
				<button class="files-layout-switcher files-layout-switcher-details" data-layout="details" title="<?= @text('Show files in a list'); ?>">
					<?= @text('Details'); ?>
				</button>
			</div>
		</div>
		<div class="view -koowa-box-scroll -koowa-box-flex">
			<div id="files-grid"></div>
		</div>
        <div class="files-layout-grid-resizer-container">
            <div class="files-layout-grid-resizer-wrap">
                <input id="files-thumbs-size" type="range" min="80" max="200" step="0.1" />
            </div>
        </div>
		<?= @helper('paginator.pagination') ?>
	
		<?= @template('uploader');?>
	</div>
	<div style="clear: both"></div>
</div>

<div>
	<div id="files-new-folder-modal" class="files-modal" style="display: none">
	<form>
		<input class="inputbox focus" type="text" id="files-new-folder-input" size="40" placeholder="<?= @text('Enter a folder name') ?>" />
		<button id="files-new-folder-create" disabled><?= @text('Create'); ?></button>
	</form>
	</div>
</div>