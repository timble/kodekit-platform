<? $list = (isset($row) && isset($table)) ? $attachments->find(array('row' => $row, 'table' => $table)) : $attachments ?>

<? if(count($list)) : ?>
    <ul class="thumbnails">
    <? foreach($list as $item) : ?>
    	<? if($item->file->isImage() && !in_array($item->id, Nooku\Library\ObjectConfig::unbox($exclude))) : ?>
    	<li class="span3">
	    	<a class="thumbnail" href="<?= @route('view=attachment&format=file&id='.$item->id) ?>" rel="{handler: 'image'}">
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