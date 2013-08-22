<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<?= object('com:files.controller.file')
	->container('files-files')
	->layout('compact')
	->types(array('image'))
	->editor($state->editor)
    ->render();
?>

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
		window.parent.Editors.get(Files.app.editor).insertText(image);
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
		var target = document.id(e.target).getParent('.files-node'),
			row = target.retrieve('row'),
    		url = row.image.replace(Files.sitebase+'/', '').replace(/sites\/[^\/]+\//, '');

		document.id('image-url').set('value', url);
	});

	if (window.parent.tinyMCE) {
		var text = window.parent.tinyMCE.activeEditor.selection.getContent({format:'text'});
		if (text) {
			document.id('image-alt').set('value', text);
		}
	}
});
</script>

<div id="image-insert-form">
	<fieldset>
        <div>
            <label for="image-url"><?= translate('URL') ?></label>
            <div>
                <input type="text" id="image-url" value="" />
            </div>
        </div>
        <div>
            <label for="image-alt"><?= translate('Description') ?></label>
            <div>
                <input type="text" id="image-alt" value="" />
            </div>
        </div>
        <div>
            <label for="image-title"><?= translate('Title') ?></label>
            <div>
                <input type="text" id="image-title" value="" />
            </div>
        </div>
        <div>
            <label for="image-align"><?= translate('Align') ?></label>
            <div>
                <select size="1" id="image-align" title="<?= translate('Positioning of this image') ?>">
                    <option value="" selected="selected"><?= translate('Not Set') ?></option>
                    <option value="left"><?= translate('Left') ?></option>
                    <option value="right"><?= translate('Right') ?></option>
                </select>
            </div>
        </div>
        <div>
            <label for="image-caption"><?= translate('Caption') ?></label>
            <div>
                <input type="checkbox" id="image-caption" />
            </div>
        </div>
	</fieldset>
	<div class="buttons">
        <button class="btn" type="button" id="close-modal"><?= translate('Cancel') ?></button>
        <button class="btn" type="button" id="insert-image"><?= translate('Insert') ?></button>
	</div>
</div>