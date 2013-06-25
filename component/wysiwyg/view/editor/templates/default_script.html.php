<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */
?>

<? /* Image and article buttons needs this in order to work */ ?>
<?= @helper('behavior.modal') ?>

<? if ($options['toggle']) : ?>
    <style src="media://wysiwyg/css/form.css" />
    <script src="media://wysiwyg/js/Fx.Toggle.js" />
<? endif ?>

<script src="media://wysiwyg/ckeditor/ckeditor.js" />


<script>
    jQuery( document ).ready(function() {

        CKEDITOR.replace( <?= $id ?>, {
            toolbar: '<?=$options['toolbar']?>',
            language: '<?=$settings['language']?>',
            height: '<?=$settings['height']?>px',
            width: '<?=$settings['width']?>',
            contentsLangDirection: '<?=$settings['directionality']?>',
            scayt_autoStartup: '<?=$settings['scayt_autoStartup']?>',
        });
    });
</script>

