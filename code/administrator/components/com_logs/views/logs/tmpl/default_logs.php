<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? foreach ($logs as $log) : ?>
<tr>
	<td align="center">
		<?= @helper('grid.checkbox',array('row' => $log)); ?>
	</td>
	<td>
		<?= $log->application ?>
	</td>
	<td>
		<?= $log->type ?>
	</td>
	<td>
		<?= $log->package ?>
	</td>
	<td>
		<?= $log->name ?>
	</td>
	<td>
		<?= $log->action ?>
	</td>
	<td>
		<?= $log->created_by_name ?>
	</td>
	<td>
		<?= $log->created_on ?>
	</td>
</tr>
<? endforeach; ?>