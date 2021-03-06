<?
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-ckeditor for the canonical source repository
 */

use Kodekit\Library;
?>

<?= import('com:files/files/initialize.html'); ?>

<ktml:script src="assets://ckeditor/js/ckeditor.files.js" />

<script>
    Files.sitebase = '<?= object('request')->getBaseUrl(); ?>';
    Files.base     = '<?= route('component=files', true, false); ?>';
    Files.token    = '<?= $token; ?>';

    window.addEvent('domready', function() {
        var config = <?= json_encode(parameter('config')); ?>,
        options = {
            state: {
                defaults: {
                    limit: '50',
                    offset: 0
                }
            },
            editor: <?= json_encode(parameter('editor')); ?>,
            tree: {
                theme: 'assets://files/images/mootree.png'
            },
            types: <?= json_encode(parameter('types')) ?>,
            site: <?= json_encode($site); ?>,
            container: <?= json_encode(parameter('container') ? parameter('container') : null); ?>
        };
        options = $extend(options, config);

        Files.app = new Ckeditor.Files(options);

        $$('#tabs-pane_insert dt').addEvent('click', function(){
            setTimeout(function(){window.fireEvent('refresh');}, 300);
        });
    });
</script>

<?= import('default_fields.html');?>

<div id="files-compact" class="tabs tabs-horizontal">
    <div class="tab">
        <input type="radio" id="tab-1" name="tab-group-1" checked="">
        <label for="tab-1"><?= translate('Insert') ?></label>
        <div id="files-insert" class="content">
            <div id="files-tree-container">
                <div id="files-tree"></div>
            </div>
            <div id="files-grid"></div>
            <div id="details">
                <div id="files-preview"></div>
                <div id="image-insert-form">
                    <input type="hidden" name="type" id="image-type" value=""/>
                    <fieldset>
                        <div>
                            <label for="image-url"><?= translate('URL') ?></label>
                            <div>
                                <input type="text" id="image-url" value="" />
                            </div>
                        </div>
                        <? if(in_array('file', parameter('types'))) : ?>
                            <div id="link-text">
                                <label for="image-text"><?= translate('Text') ?></label>
                                <div>
                                    <input type="text" id="image-text" value="" />
                                </div>
                            </div>
                        <?endif;?>
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
                        <? if(in_array('image', parameter('types'))) : ?>
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
                        <? endif; ?>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
    <div class="tab">
        <input type="radio" id="tab-2" name="tab-group-1">
        <label for="tab-2"><?= translate('Upload') ?></label>
        <div class="content">
            <?= import('com:files/files/uploader.html'); ?>
        </div>
    </div>
</div>