<?php
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<? if ($params->get('show_page_title', 1)): ?>
<div class="page-header">
	<h1><?= @escape($params->get('page_title')); ?></h1>
</div>
<? endif; ?>

<form action="" method="get" class="-koowa-form">
<table class="table table-striped">
	<thead>
		<tr>
			<th><?=@text('Name')?></th>
			<th><?=@text('Size')?></th>
			<th><?=@text('Type')?></th>
		</tr>
	</thead>
	<tbody>
		<? if ($parent !== null): ?>
		<tr>
			<td colspan="3">
				<i class="icon-chevron-left"></i>
				<a href="<?= @route('&view=folder&folder='.$parent); ?>">
					<?= @text('Parent Folder') ?>
				</a>
			</td>
		</tr>
		<? endif; ?>
		<? foreach($folders as $folder): ?>
		<tr>
			<td>
				<i class="icon-folder-close"></i>
				<a href="<?= @route('&view=folder&folder='.$folder->path);?>">
					<?=@escape($folder->display_name)?>
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
				<a class="files-download" data-path="<?= @escape($file->path); ?>"
					href="<?= @route('&view=file&folder='.$state->folder.'&name='.$file->name);?>">
					<?=@escape($file->display_name)?>
				</a>
			</td>
			<td>
				<?= @helper('com:files.filesize.humanize', array('size' => $file->size));?>
			</td>
			<td>
				<?= $file->extension; ?>
			</td>
		</tr>
		<? endforeach; ?>
	</tbody>
</table>

<? if(count($files) != $total): ?>
    <?= @helper('paginator.pagination', array(
    	'total' => $total, 
    	'show_limit' => false, 
    	'show_count' => false,
    	'limit' => $state->limit,
    	'offset' => $state->offset
    )); ?>
<? endif; ?>
</form>
