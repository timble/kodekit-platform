<? defined('_JEXEC') or die('Restricted access'); ?>

<? $i = 0; $m = 0; ?>
<? foreach (@$offices as $office) : ?>
	<tr class="<?php if ($office->odd) { echo 'even'; } else { echo 'odd'; } ?>">
		<td>
			<?= $i + 1; ?>
		</td>
		<td>
			<a href="<?=@route('option=com_beer&view=office&id='.$office->id) ?>" />
				<?= @$escape($office->title); ?>
			</a>
		</td>
	</tr>
	<? $i = $i + 1; $m = (1 - $m); ?>
<? endforeach; ?>