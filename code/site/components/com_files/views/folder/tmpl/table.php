<?
/**
 * @package     FILEman
 * @copyright   Copyright (C) 2012 Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.joomlatools.com
 */
defined('_JEXEC') or die; ?>

<? if ($params->get('show_page_title')): ?>
	<h2>
		<?= @escape($params->get('page_title')); ?>
	</h2>
<? endif; ?>

<form action="" method="get" class="-koowa-form">
<table class="table table-striped">
	<thead>
		<tr>
			<th><?=@text('Name')?></th>
			<th><?=@text('Size')?></th>
		</tr>
	</thead>
	<? if ($state->limit && $total > $state->limit-$state->offset): ?>
	<tfoot>
	    <tr>
	        <td colspan="2">
	        	
	        </td>
	    </tr>
	</tfoot>
	<? endif; ?>
	<tbody>
	    <? $i = 0 ?>
		<? if ($parent !== null): ?>
		<tr class="<?= $i++ % 2 ? 'even' : 'odd' ?>">
			<td>
				<a href="<?= @route('folder='.$parent); ?>">
					<?= @text('Parent Folder') ?>
				</a>
			</td>
			<td></td>
		</tr>
		<? endif; ?>
		<? if ($folders): foreach($folders as $folder): ?>
		<tr class="<?= $i++ % 2 ? 'even' : 'odd' ?>">
			<td>
				<a href="<?= @route('folder='.$folder->path);?>">
					<?=@escape($folder->display_name)?>
				</a>
			</td>
			<td></td>
		</tr>
		<? endforeach; endif; ?>
		<? if ($files): foreach($files as $file): ?>
		<tr class="<?= $i++ % 2 ? 'even' : 'odd' ?>">
			<td>
				<a class="fileman-download" data-path="<?= @escape($file->path); ?>"
					href="<?= @route('view=file&container='.$state->container->slug.'&folder='.$state->folder.'&name='.$file->name);?>">
					<?=@escape($file->display_name)?>
				</a>
			</td>
			<td>
				<?= @helper('com://admin/files.template.helper.filesize.humanize', array('size' => $file->size));?>
			</td>
		</tr>
		<? endforeach; endif; ?>
	</tbody>
</table>

<? if(count($files) != $total): ?>
    <?= @helper('paginator.pagination', array('total' => $total, 'show_limit' => false, 'show_count' => false)); ?>
<? endif; ?>
</form>
