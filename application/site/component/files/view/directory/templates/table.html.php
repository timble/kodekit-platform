<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<? if ($params->get('show_page_title', 1)): ?>
<div class="page-header">
	<h1><?= escape($params->get('page_title')); ?></h1>
</div>
<? endif; ?>

<form action="" method="get" class="-koowa-form">
<table class="table table-striped">
	<thead>
		<tr>
			<th style="width: 70%"><?=translate('Name')?></th>
            <? if(count($files)) : ?>
            <th><?=translate('Size')?></th>
            <th><?=translate('Type')?></th>
            <? endif ?>
		</tr>
	</thead>
	<tbody>
		<? foreach($folders as $folder): ?>
		<tr>
			<td colspan="3">
				<i class="icon-folder-close"></i>
				<a href="<?= route('view=folder&folder='.$folder->path);?>">
					<?=escape($folder->display_name)?>
				</a>
			</td>
			<td></td>
			<td></td>
		</tr>
		<? endforeach; ?>

		<? foreach($files as $file): ?>
		<tr>
			<td>
				<i class="icon-file"></i>
				<a class="files-download" data-path="<?= escape($file->path); ?>"
					href="<?= route('view=file&folder='.$file->folder.'&name='.$file->name);?>">
					<?=escape($file->display_name)?>
				</a>
			</td>
			<td>
				<?= helper('com:files.filesize.humanize', array('size' => $file->size));?>
			</td>
			<td>
				<?= $file->extension; ?>
			</td>
		</tr>
		<? endforeach; ?>
	</tbody>
</table>

<? if(count($files) != parameter('total')): ?>
    <?= helper('paginator.pagination', array(
        'limit'      => parameter('limit'),
        'offset'     => parameter('offset'),
    	'show_limit' => false,
    	'show_count' => false
    )); ?>
<? endif; ?>
</form>
