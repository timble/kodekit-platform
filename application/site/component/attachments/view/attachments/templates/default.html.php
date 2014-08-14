<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<?= helper('behavior.mootools') ?>
<?= helper('behavior.modal') ?>

<? $list = (isset($row) && isset($table)) ? $attachments->find(array('row' => $row, 'table' => $table)) : $attachments ?>

<? if(count($list) > '1' || (count($list) == '1' && reset($exclude) == '0')) : ?>
    <ul class="thumbnails">
    <? foreach($list as $item) : ?>
    	<? if($item->file->isImage() && !in_array($item->id, Nooku\Library\ObjectConfig::unbox($exclude))) : ?>
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