<? $list = (isset($row) && isset($table)) ? $attachments->find(array('row' => $row, 'table' => $table)) : $attachments ?>

<script src="media://attachments/js/attachments.list.js" />
<script src="media://files/js/uri.js" />

<script>
window.addEvent('domready', function() {
    new Attachments.List({
        container: 'attachments-list',
        action: '<?= @route('view=attachments') ?>',
        token: '<?= $this->getService('user')->getSession()->getToken() ?>'
    });
});
</script>

<? if(count($list)) : ?>
    <div id="attachments-list">
    <ul class="thumbnails" style="padding-left:0;">
    <? foreach($list as $item) : ?>
    	<? if($item->file->isImage()) : ?>
    	<li style="width: 130px;">
	    	<div class="thumbnail">
                <a class="modal" href="<?= @route('view=attachment&format=file&id='.$item->id) ?>" rel="{handler: 'image'}">
                    <img src="<?= $item->thumbnail->thumbnail ?>" />
                </a>
                <div class="caption">                    
                    <a class="btn btn-mini btn-danger" href="#" data-action="delete" data-id="<?= $item->id; ?>">
                        <i class="icon-trash icon-white"></i>
                    </a>
                    <a class="btn btn-mini" href="#" data-action="assign" data-id="<?= $item->id; ?>" data-row="<?= $article->id ?>">
                        <i class="icon-star"></i>
                    </a>
                </div>
            </div>
    	</li>
    	<? endif ?>
    <? endforeach ?>
	</ul>
    
    <ul>
    <? foreach($list as $item) : ?>        
    	<? if(!$item->file->isImage()) : ?>
    	<li>
            <a href="<?= @route('view=attachment&format=file&id='.$item->id) ?>"><?= @escape($item->name) ?></a>
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