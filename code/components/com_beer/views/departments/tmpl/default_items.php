<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? $i = 0; $m = 0; ?>
<? foreach (@$departments as $department) : ?>
	<tr class="<?php echo 'sectiontableentry'.$m; ?>">
		<td>
			<?= $i + 1; ?>
		</td>
		<td>
			<a href="<?=@route('option=com_beer&view=department&id='.$department->slug) ?>" />
				<?= @$escape($department->title); ?>
			</a>
		</td>
		<td>
				<?= @$escape($department->people); ?>
		</td>
	</tr>
	<? $i = $i + 1; $m = (1 - $m); ?>
<? endforeach; ?>