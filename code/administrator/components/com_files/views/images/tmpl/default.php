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

<?= @service('com://admin/files.controller.file')
	->container('files-files')
	->layout('compact')
	->types(array('image'))
	->editor($state->editor)
	->display(); ?>

<script>
window.addEvent('domready', function() {
	var getImageString = function() {
		var src = document.id('image-url').get('value');
		var attrs = {};
		['align', 'alt', 'title'].each(function(id) {
			var value = document.id('image-'+id).get('value');
			if (value) {
				attrs[id] = value;
			}
		});
		if (document.id('image-caption').get('value')) {
			attrs['class'] = 'caption';
		}

		var str = '<img src="'+src+'" ';
		var parts = [];
		$each(attrs, function(value, key) {
			parts.push(key+'="'+value+'"');
		});
		str += parts.join(' ')+' />';

		return str;
	};
	var insertImage = function() {
		var image = getImageString();
		window.parent.jInsertEditorText(image, Files.app.editor);
	};
	
	document.id('insert-image').addEvent('click', function(e) {
		e.stop();
		insertImage();
		window.parent.SqueezeBox.close();
	});
	document.id('close-modal').addEvent('click', function(e) {
		e.stop();
		window.parent.SqueezeBox.close();
	});

	document.id('details').adopt(document.id('image-insert-form'));

	Files.app.grid.addEvent('clickImage', function(e) {
		var target = document.id(e.target).getParent('.files-node');
		var row = target.retrieve('row');
		
		document.id('image-url').set('value', Files.path.replace(/sites\/[^\/]+\//, '')+'/'+row.path);
	});
});
</script>

<div id="image-insert-form">
	<table class="properties">
		<tr>
			<td><label for="image-url"><?= @text('URL') ?></label></td>
			<td><input type="text" id="image-url" value="" /></td>
		</tr>
		<tr>
			<td><label for="image-alt"><?= @text('Description') ?></label></td>
			<td><input type="text" id="image-alt" value="" /></td>
		</tr>
		<tr>
			<td><label for="image-title"><?= @text('Title') ?></label></td>
			<td><input type="text" id="image-title" value="" /></td>
		</tr>
		<tr>
			<td><label for="image-align"><?= @text('Align') ?></label></td>
			<td>
				<select size="1" id="image-align" title="Positioning of this image">
					<option value="" selected="selected"><?= @text('Not Set') ?></option>
					<option value="left"><?= @text('Left') ?></option>
					<option value="right"><?= @text('Right') ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td><label for="image-caption"><?= @text('Caption') ?></label></td>
			<td><input type="checkbox" id="image-caption" /></td>
		</tr>
	</table>
	<div class="buttons">
		<button type="button" id="insert-image"><?= @text('Insert') ?></button>
		<button type="button" id="close-modal"><?= @text('Cancel') ?></button>
	</div>
</div>