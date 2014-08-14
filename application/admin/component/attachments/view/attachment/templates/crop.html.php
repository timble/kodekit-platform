<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
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
            token: '<?= $this->getObject('user')->getSession()->getToken() ?>'
        });
    });
</script>

<div id="attachment">
    <img id="target" src="files/<?= $this->getObject('application')->getSite() ?>/attachments/<?= $attachment->path ?>" />
    <a class="button btn-success btn-block" style="margin-top: 10px" href="#" data-action="crop" data-id="<?= $attachment->id; ?>">
        <?= translate('Save') ?>
    </a>
</div>