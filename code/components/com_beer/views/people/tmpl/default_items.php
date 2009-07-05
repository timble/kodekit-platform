<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? $i = 0; $m = 0; ?>
<? foreach (@$people as $person) : ?>
	<tr class="<?php echo 'sectiontableentry'.$m; ?>">
		<td>
			<?= $i + 1; ?>
		</td>
		<td>
			<a href="<?=@route('option=com_beer&view=person&id='.$person->slug) ?>" />
				<?= @$escape($person->name); ?>
			</a>
		</td>
		<td>
			<?= @$escape($person->position); ?>
		</td>
		<td>
			<a href="<?=@route('option=com_beer&view=office&id='.$person->office_slug) ?>" />
				<?= @$escape($person->office); ?>
			</a>
		</td>
		<td>
			<a href="<?=@route('option=com_beer&view=department&id='.$person->department_slug) ?>" />
				<?= @$escape($person->department); ?>
			</a>
		</td>
	</tr>
	<? $i = $i + 1; $m = (1 - $m); ?>
<? endforeach; ?>