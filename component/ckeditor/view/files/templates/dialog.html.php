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
    ->layout('browser')
    ->types(array('file'))
    ->editor($state->editor)
    ->render();
?>

<script>
    window.addEvent('domready', function() {
        document.id('details').adopt(document.id('image-insert-form'));

        Files.app.grid.addEvent('clickFile', function(e) {
            var target = document.id(e.target).getParent('.files-node'),
                row = target.retrieve('row'),
                path = row.baseurl+"/"+row.name,
                url = path.replace(Files.sitebase+'/', '').replace(/sites\/[^\/]+\//, '');

            document.id('file-url').set('value', url);
            document.id('file-link').set('value', row.name);
        });

    });
</script>

<div id="file-insert-form">
    <fieldset>
        <div>
            <label for="file-url"><?= @text('URL') ?></label>
            <div>
                <input type="text" id="file-url" value="" />
            </div>
        </div>
        <div>
            <label for="file-link"><?= @text('Link') ?></label>
            <div>
                <input type="text" id="file-link" value="" />
            </div>
        </div>
        <div>
            <label for="file-alt"><?= @text('Description') ?></label>
            <div>
                <input type="text" id="file-alt" value="" />
            </div>
        </div>
        <div>
            <label for="file-title"><?= @text('Title') ?></label>
            <div>
                <input type="text" id="file-title" value="" />
            </div>
        </div>
    </fieldset>
</div>