<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<script src="assets://ckeditor/ckeditor/ckeditor.js" />

<script>
    jQuery(document).ready(function() {
        CKEDITOR.replace( <?= $id ?>, {
            baseHref   : '<?= $settings->baseHref ?>',
            toolbar    : '<?= $settings->options->toolbar ?>',
            height     : '<?= $settings->height ?>',
            width      : '<?= $settings->width ?>',
            language   : '<?= $settings->language ?>',
            contentsLanguage     : '<?= $settings->contentsLanguage ?>',
            contentsLangDirection: '<?= $settings->contentsLangDirection ?>',
            scayt_autoStartup    : '<?= $settings->scayt_autoStartup ?>'
        });
    });
</script>

<textarea id="<?= $id ?>" name="<?= $name ?>" class="ckeditor editable-<?= $id ?> validate-editor" style="visibility:hidden"><?= $text ?></textarea>
