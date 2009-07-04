<? defined('_JEXEC') or die('Restricted access'); ?>

<? $i = 0; $m = 0; ?>
<? foreach (@$people as $person) : ?>
	<tr class="<?php echo 'sectiontableentry'.$m; ?>">
		<td>
			<?= $i + 1; ?>
		</td>
		<td>
			<a href="<?=@route('option=com_beer&view=person&id='.$person->id) ?>" />
				<?= @$escape($person->name); ?>
			</a>
		</td>
		<td>
			<?= @$escape($person->position); ?>
		</td>
		<td>
			<a href="<?=@route('option=com_beer&view=office&id='.$person->beer_office_id) ?>" />
				<?= @$escape($person->office); ?>
			</a>
		</td>
		<td>
			<a href="<?=@route('option=com_beer&view=department&id='.$person->beer_department_id) ?>" />
				<?= @$escape($person->department); ?>
			</a>
		</td>
	</tr>
	<? $i = $i + 1; $m = (1 - $m); ?>
<? endforeach; ?>