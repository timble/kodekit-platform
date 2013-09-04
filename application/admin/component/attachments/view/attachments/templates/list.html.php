<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<? $list = (isset($row) && isset($table)) ? $attachments->find(array('row' => $row, 'table' => $table)) : $attachments ?>

<?= helper('behavior.modal') ?>

<script src="assets://attachments/js/attachments.list.js" />
<script src="assets://files/js/uri.js" />

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
        <div class="thumbnail">
            <a class="modal" href="<?= route('view=attachment&format=file&id='.$item->id) ?>" rel="{handler: 'image'}">
                <img src="<?= $item->thumbnail->thumbnail ?>" />
            </a>
            <div class="thumbnail__caption">
                <a class="btn btn-mini btn-danger" href="#" data-action="delete" data-id="<?= $item->id; ?>">
                    <i class="icon-trash icon-white"></i>
                </a>
                <? if(isset($attachments_attachment_id)) : ?>
                <input type="radio" name="attachments_attachment_id" id="fav-<?= $item->id; ?>" value="<?= $item->id; ?>" <?= $item->id == $attachments_attachment_id ? 'checked' : '' ?>>
                <label for="fav-<?= $item->id; ?>" class="btn btn-mini">
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
            <div class="caption btn-group">
                <a class="btn btn-mini btn-danger" href="#" data-action="delete" data-id="<?= $item->id; ?>">
                    <i class="icon-trash icon-white"></i>
                </a>
            </div>
        </li>
    	<? endif ?>
    <? endforeach ?>
    </ul>

    </div>
<? endif ?>