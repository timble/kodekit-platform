<?
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-ckeditor for the canonical source repository
 */
?>

<ktml:script src="assets://js/koowa.js" />
<ktml:script src="assets://ckeditor/ckeditor/ckeditor.js" />
<ktml:script src="assets://ckeditor/js/ckeditor.koowa.js" />

<? $options = new  Kodekit\Library\ObjectConfig($options);  ?>

<script>
    jQuery(document).ready(function() {
        CKEDITOR.replace( '<?= $id ?>', {
            baseHref   : '<?= $options->baseHref ?>',
            toolbar    : '<?= $options->toolbar ?>',
            height     : '<?= $options->height ?>',
            width      : '<?= $options->width ?>',
            language   : '<?= $options->language ?>',
            contentsLanguage     : '<?= $options->contentsLanguage ?>',
            contentsLangDirection: '<?= $options->contentsLangDirection ?>',
            scayt_autoStartup    : '<?= $options->scayt_autoStartup ?>',
            removeButtons        : '<?= $options->removeButtons ?>'
        });
    });
</script>

<textarea id="<?= $id ?>" name="<?= $name ?>" class="ckeditor editable-<?= $id ?> validate-editor <?= $class ?>" style="visibility:hidden"><?= $text ?></textarea>
