<?
/**
 * @package     Nooku_Components
 * @subpackage  Ckeditor
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>
<script src="media://files/js/jquery-1.8.0.min.js" />
<script type="text/javascript">
    var $jQuery = jQuery.noConflict();
</script>
<?= @object('com:ckeditor.controller.file')
    ->container('files-files')
    ->layout('dialog')
    ->types(array('image'))
    ->editor($state->editor)
    ->render();
?>

<script>
    window.addEvent('domready', function() {
        document.id('details').adopt(document.id('image-insert-form'));

        Files.app.grid.addEvent('clickImage', function(e) {
            var target = document.id(e.target).getParent('.files-node'),
                row = target.retrieve('row'),
                url = row.image.replace(Files.sitebase+'/', '').replace(/sites\/[^\/]+\//, '');

            document.id('image-url').set('value', url);
        });

    });
</script>

<div id="image-insert-form">
    <fieldset>
        <div>
            <label for="image-url"><?= @text('URL') ?></label>
            <div>
                <input type="text" id="image-url" value="" />
            </div>
        </div>
        <div>
            <label for="image-alt"><?= @text('Description') ?></label>
            <div>
                <input type="text" id="image-alt" value="" />
            </div>
        </div>
        <div>
            <label for="image-title"><?= @text('Title') ?></label>
            <div>
                <input type="text" id="image-title" value="" />
            </div>
        </div>
        <div>
            <label for="image-align"><?= @text('Align') ?></label>
            <div>
                <select size="1" id="image-align" title="<?= @text('Positioning of this image') ?>">
                    <option value="" selected="selected"><?= @text('Not Set') ?></option>
                    <option value="left"><?= @text('Left') ?></option>
                    <option value="right"><?= @text('Right') ?></option>
                </select>
            </div>
        </div>
    </fieldset>
</div>