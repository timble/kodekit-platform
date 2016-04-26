<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>
<?= helper('behavior.jquery'); ?>
<ktml:script src="assets://files/js/bootstrap-modal.js" />
<ktml:script src="assets://files/js/bootstrap-image-gallery.js" />
<ktml:script src="assets://files/js/gallery.js" />

<script>
jQuery(function($) {
    new ComFiles.Gallery($('div.files-gallery'), {thumbwidth: <?= json_encode((int)$thumbnail_size['x']) ?>});
});
</script>

<div class="com_files files-gallery">
    <div id="modal-gallery" class="modal modal-gallery hide fade">
        <div class="modal-header">
            <a class="close" style="cursor: pointer" data-dismiss="modal">&times;</a>
            <h3 class="modal-title"></h3>
        </div>
        <div class="modal-body"><div class="modal-image"></div></div>
    </div>

	<div class="page-header">
		<h1><?= escape($params->get('page_title')); ?></h1>
	</div>

    <? if (count($folders)): ?>
    <ul>
    <? foreach($folders as $folder): ?>
	<li class="gallery-folder">
	    <a href="<?= route('view=folder&folder='.$folder->path) ?>">
	        <?= escape($folder->display_name) ?>
	    </a>
	</li>
	<? endforeach ?>
	</ul>
	<? endif ?>

	<? if (count($files)): ?>
    <ol class="thumbnails files-gallery" data-toggle="modal-gallery" data-target="#modal-gallery" data-selector="a.thumbnail">
        <? foreach($files as $file): ?>
    	<? if (!empty($file->thumbnail)): ?>
        <li class="span3">
    		<a class="thumbnail text-center" data-path="<?= escape($file->path); ?>"
    			href="<?= route('view=file&folder='.$file->folder.'&name='.$file->name) ?>"
    		    title="<?= escape($file->display_name) ?>"
    		    style="min-height:<?= $thumbnail_size['y'] ?>px"
            >
        		<img src="<?= $file->thumbnail ?>" alt="<?= escape($file->display_name) ?>" />
        	</a>
        </li>
    	<? endif ?>
        <? endforeach ?>
    </ol>

    <? if(count($files) != parameter('total')): ?>
	    <?= helper('paginator.pagination', array(
	    	'limit' => parameter('limit'),
	    	'offset' => parameter('offset'),
	    	'show_count' => false,
	    	'show_limit' => false
	    )) ?>
    <? endif ?>

    <? endif ?>
</div>
