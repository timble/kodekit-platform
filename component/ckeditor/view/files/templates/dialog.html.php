<?
/**
 * @package     Nooku_Components
 * @subpackage  Ckeditor
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @object('com:ckeditor.controller.file')
    ->container('files-files')
    ->layout('browser')
    ->types(array($type))
    ->editor($state->editor)
    ->render();
?>

<script>
    window.addEvent('domready', function() {
        document.id('details').adopt(document.id('image-insert-form'));

        var type = '<?=$type?>';

        if(type == 'file'){
            Files.app.grid.addEvent('clickFile', function(e) {
                var target = document.id(e.target).getParent('.files-node');
                var row = target.retrieve('row');

                var path = row.baseurl+"/"+row.name;
                var url = path.replace(Files.sitebase+'/', '').replace(/sites\/[^\/]+\//, '');

                document.id('image-url').set('value', url);

                document.id('image-type').set('value',row.metadata.mimetype);
                if(document.id('image-text').get('value') == ""){
                    document.id('image-text').set('value', row.name);
                }

            });
        }else{

            Files.app.grid.addEvent('clickImage', function(e) {
                var target = document.id(e.target).getParent('.files-node');
                var row = target.retrieve('row');

                var    url = row.image.replace(Files.sitebase+'/', '').replace(/sites\/[^\/]+\//, '');
                document.id('image-url').set('value', url);

                document.id('image-type').set('value',row.metadata.mimetype);
            });
        }

    });
</script>

<div id="image-insert-form">
    <input type="hidden" name="type" id="image-type" value=""/>
    <fieldset>
        <div>
            <label for="image-url"><?= @text('URL') ?></label>
            <div>
                <input type="text" id="image-url" value="" />
            </div>
        </div>
        <?if($type == 'file'):?>
            <div id="link-text">
                <label for="image-text"><?= @text('Text') ?></label>
                <div>
                    <input type="text" id="image-text" value="" />
                </div>
            </div>
        <?endif;?>
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
        <?if($type == 'image'):?>
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
        <?else:?>
            <div>
                <label for="image-align"><?= @text('Target') ?></label>
                <div>
                    <select size="1" id="target" title="<?= @text('Target of the link') ?>">
                        <option value="" selected="selected"><?= @text('Not Set') ?></option>
                        <option value="_blank"><?= @text('_blank') ?></option>
                        <option value="_top"><?= @text('_top') ?></option>
                        <option value="_self"><?= @text('_self') ?></option>
                        <option value="_parent"><?= @text('_parent') ?></option>
                    </select>
                </div>
            </div>
        <?endif;?>
    </fieldset>
</div>