<? defined('_JEXEC') or die('Restricted access'); ?>

<? $i = 0; $m = 0; ?>
<? foreach (@$people as $person) : ?>
	<tr class="<?php if ($person->odd) { echo 'even'; } else { echo 'odd'; } ?>">
		<td>
			<?= $i + 1; ?>
		</td>
		<td>
			<a href="<?=@route('option=com_beer&view=person&id='.$person->id) ?>" />
				<?= @$escape($person->firstname); ?>
			</a>
		</td>
	</tr>
	<? $i = $i + 1; $m = (1 - $m); ?>
<? endforeach; ?>