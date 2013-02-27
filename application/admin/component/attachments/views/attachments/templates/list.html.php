<? $list = (isset($row) && isset($table)) ? $attachments->find(array('row' => $row, 'table' => $table)) : $attachments ?>

<? if(count($list)) : ?>
    <ul class="thumbnails" style="padding-left:0;">
    <? foreach($list as $item) : ?>
    	<? if($item->file->isImage()) : ?>
    	<li style="width: 130px;">
	    	<div class="thumbnail">
                <a class="" href="<?= @route('view=attachment&format=file&id='.$item->id) ?>" rel="{handler: 'image'}">
                    <img src="<?= $item->thumbnail->thumbnail ?>" />
                </a>
                <div class="caption">                    
                    <a class="btn btn-mini btn-danger" href="#">
                        <i class="icon-trash icon-white"></i>
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
                <a class="btn btn-mini btn-danger" href="#">
                    <i class="icon-trash icon-white"></i>
                </a>
            </div>
        </li>
    	<? endif ?>
    <? endforeach ?>
    </ul>
<? endif ?>