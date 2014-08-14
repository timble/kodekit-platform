<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<? $list = (isset($row) && isset($table)) ? $attachments->find(array('row' => $row, 'table' => $table)) : $attachments ?>

<?= helper('behavior.modal') ?>

<ktml:script src="assets://attachments/js/attachments.list.js" />
<ktml:script src="assets://files/js/uri.js" />

<script>
window.addEvent('domready', function() {
    new Attachments.List({
        container: 'attachments-list',
        action: '<?= route('view=attachments') ?>',
        token: '<?= $this->getObject('user')->getSession()->getToken() ?>'
    });
});
</script>

<? if(count($list)) : ?>
    <div id="attachments-list">
    <? foreach($list as $item) : ?>
    	<? if($item->file->isImage()) : ?>
        <div class="thumbnail" data-id="<?= $item->id; ?>">
            <a class="modal" href="files/<?= $this->getObject('application')->getSite() ?>/attachments/<?= $item->path ?>" rel="{handler: 'image'}">
                <img src="files/<?= $this->getObject('application')->getSite() ?>/attachments/<?= $item->thumbnail ?>" />
            </a>
            <div class="thumbnail__caption">
                <a class="button btn-mini btn-danger" href="#" data-action="delete" data-id="<?= $item->id; ?>">
                    <i class="icon-trash icon-white"></i>
                </a>
                <a class="button btn-mini modal" href="<?= route('view=attachment&layout=crop&tmpl=overlay&id='.$item->id) ?>" rel="{handler: 'iframe', size: {x: 600, y: 635}}">
                    <i class="icon-resize-small icon-white"></i>
                </a>
                <? if(isset($attachments_attachment_id)) : ?>
                <input type="radio" name="attachments_attachment_id" id="fav-<?= $item->id; ?>" value="<?= $item->id; ?>" <?= $item->id == $attachments_attachment_id ? 'checked' : '' ?>>
                <label for="fav-<?= $item->id; ?>" class="button btn-mini">
                    <i class="icon-star"></i>
                </label>
                <? endif ?>
            </div>
        </div>
    	<? endif ?>
    <? endforeach ?>
    
    <ul>
    <? foreach($list as $item) : ?>        
    	<? if(!$item->file->isImage()) : ?>
    	<li>
            <a href="<?= route('view=attachment&format=file&id='.$item->id) ?>"><?= escape($item->name) ?></a>
            <div class="caption button__group">
                <a class="button btn-mini btn-danger" href="#" data-action="delete" data-id="<?= $item->id; ?>">
                    <i class="icon-trash icon-white"></i>
                </a>
            </div>
        </li>
    	<? endif ?>
    <? endforeach ?>
    </ul>

    </div>
<? endif ?>