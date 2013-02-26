<? $list = (isset($row) && isset($table)) ? $attachments->find(array('row' => $row, 'table' => $table)) : $attachments ?>

<? if(count($list)) : ?>
    <ul class="thumbnails" style="padding-left:0;">
    <? foreach($list as $item) : ?>
    	<? if($item->file->isImage()) : ?>
    	<li style="width: 100px;">
	    	<a class="modal thumbnail" href="<?= @route('view=attachment&format=file&id='.$item->id) ?>" rel="{handler: 'image'}">
	    	   <img src="<?= $item->thumbnail->thumbnail ?>" />
	    	</a>
    	</li>
    	<? endif ?>
    <? endforeach ?>
	</ul>
    
    <ul>
    <? foreach($list as $item) : ?>        
    	<? if(!$item->file->isImage()) : ?>
    	<li><a href="<?= @route('view=attachment&format=file&id='.$item->id) ?>"><?= @escape($item->name) ?></a> </li>
    	<? endif ?>
    <? endforeach ?>
    </ul>
<? endif ?>