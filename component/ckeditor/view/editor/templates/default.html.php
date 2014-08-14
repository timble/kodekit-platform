<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<ktml:script src="assets://ckeditor/ckeditor/ckeditor.js" />
<ktml:script src="assets://ckeditor/js/ckeditor.koowa.js" />

<script>
    jQuery(document).ready(function() {
        CKEDITOR.replace( '<?= $id ?>', {
            baseHref   : '<?= $settings->baseHref ?>',
            toolbar    : '<?= $settings->options->toolbar ?>',
            height     : '<?= $settings->height ?>',
            width      : '<?= $settings->width ?>',
            language   : '<?= $settings->language ?>',
            contentsLanguage     : '<?= $settings->contentsLanguage ?>',
            contentsLangDirection: '<?= $settings->contentsLangDirection ?>',
            scayt_autoStartup    : '<?= $settings->scayt_autoStartup ?>',
            removeButtons        : '<?= $settings->removeButtons ?>'
        });
    });
</script>

<textarea id="<?= $id ?>" name="<?= $name ?>" class="ckeditor editable-<?= $id ?> validate-editor <?= $class ?>" style="visibility:hidden"><?= $text ?></textarea>
