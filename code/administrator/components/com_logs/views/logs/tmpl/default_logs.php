<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<?
$timeago = '';
foreach ($logs as $log) : 
	$timeago_text = @timeago($log->created_on);
	$show_timeago = ($timeago != $timeago_text);
	$timeago = $timeago_text;

?>
	<?php if ($show_timeago): ?>
		<tr>
			<td class="-logs-timeago" colspan="4">
				<?=($show_timeago) ? $timeago_text: ''?>
			</td>
		</tr>
	<?php endif ?>

	<tr>
		<td style="text-align:center">
			<?= @helper('grid.checkbox',array('row' => $log)); ?>
		</td>

		<td class="-logs-when">
			<?=@date($log->created_on)?>
		</td>

		<td class="-logs-message">

			<span class="-logs-createdby"><?=$log->created_by_name?></span> 

			performed 
			
			<span class="-logs-action"><?=ucfirst($log->action)?></span> 

			in 

			<span class="-logs-package"><?=ucfirst($log->package)?></span> 

			<?=@text('Component')?>&rsquo;s <?=ucfirst($log->name)?>.
		</td>

		<td class="-logs-item">

		<?php if ($log->action != 'delete'): ?>
		<a href="<?=@route('index.php?option=com_'.$log->package.'&view='.$log->name.'&id='.$log->row_id)?>" target="new"><?=$log->title?></a>
		<?php else: ?>
			<span class="-logs-deleted"><?=$log->title?></span>
		<?php endif ?>

		</td>
		
	</tr>
<? endforeach; ?>