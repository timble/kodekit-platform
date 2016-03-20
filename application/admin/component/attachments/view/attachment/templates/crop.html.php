<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<ktml:script src="assets://attachments/js/jquery.Jcrop.min.js" />
<ktml:style src="assets://attachments/css/jquery.Jcrop.min.css" />

<ktml:script src="assets://js/koowa.js" />
<ktml:script src="assets://attachments/js/attachments.list.js" />
<ktml:script src="assets://files/js/uri.js" />

<script>
    window.addEvent('domready', function() {
        new Attachments.List({
            container: 'attachment',
            action: '<?= route('view=attachment&format=json&layout=crop&id='.$attachment->id) ?>',
            token: '<?= object('user')->getSession()->getToken() ?>'
        });
    });
</script>

<div id="attachment">
    <img id="target" src="files/<?= object('application')->getSite() ?>/attachments/<?= $attachment->path ?>" />
    <a class="button btn-success btn-block" style="margin-top: 10px" href="#" data-action="crop" data-id="<?= $attachment->id; ?>">
        <?= translate('Save') ?>
    </a>
</div>