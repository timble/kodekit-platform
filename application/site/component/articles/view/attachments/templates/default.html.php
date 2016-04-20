<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.kodekit'); ?>
<?= helper('behavior.modal') ?>

<? $list = (isset($row) && isset($table)) ? $attachments->find(array('row' => $row, 'table' => $table)) : $attachments ?>

<? if(count($list) > '1' || (count($list) == '1' && reset($exclude) == '0')) : ?>
    <ul class="thumbnails">
    <? foreach($list as $item) : ?>
    	<? if($item->file->isImage() && !in_array($item->id, Kodekit\Library\ObjectConfig::unbox($exclude))) : ?>
    	<li class="span3">
	    	<a class="thumbnail modal" href="attachments://<?= $item->path; ?>" rel="{handler: 'image'}">
	    	   <img src="attachments://<?= $item->thumbnail ?>" />
	    	</a>
    	</li>
    	<? endif ?>
    <? endforeach ?>
	</ul>

    <ul>
    <? foreach($list as $item) : ?>
    	<? if(!$item->file->isImage()) : ?>
        <li><a href="attachments://<?= $item->path; ?>"><?= escape($item->name) ?></a> (<?= helper('com:files.filesize.humanize', array('size' => $item->file->size));?>, <?= $item->file->extension ?>)</li>
    	<? endif ?>
    <? endforeach ?>
    </ul>
<? endif ?>