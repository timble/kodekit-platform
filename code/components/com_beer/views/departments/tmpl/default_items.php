<? defined('_JEXEC') or die('Restricted access'); ?>

<? $i = 0; $m = 0; ?>
<? foreach (@$departments as $department) : ?>
	<tr class="<?php if ($department->odd) { echo 'even'; } else { echo 'odd'; } ?>">
		<td>
			<?= $i + 1; ?>
		</td>
		<td>
			<a href="<?=@route('option=com_beer&view=department&id='.$department->id) ?>" />
				<?= @$escape($department->title); ?>
			</a>
		</td>
	</tr>
	<? $i = $i + 1; $m = (1 - $m); ?>
<? endforeach; ?>