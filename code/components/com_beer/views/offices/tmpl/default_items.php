<? defined('_JEXEC') or die('Restricted access'); ?>

<? $i = 0; $m = 0; ?>
<? foreach (@$offices as $office) : ?>
	<tr class="<?php echo 'sectiontableentry'.$m; ?>">
		<td>
			<?= $i + 1; ?>
		</td>
		<td>
			<a href="<?=@route('option=com_beer&view=office&id='.$office->id) ?>" />
				<?= @$escape($office->title); ?>
			</a>
		</td>
		<td>
				<?= @$escape($office->Address1); ?>
		</td>
		<td>
				<?= @$escape($office->city); ?>
		</td>
		<td>
				<?= @$escape($office->state); ?>
		</td>
		<td>
				<?= @$escape($office->phone); ?>
		</td>
		<td>
				<?= @$escape($office->fax); ?>
		</td>
		<td>
				<?= @$escape($office->people); ?>
		</td>
	</tr>
	<? $i = $i + 1; $m = (1 - $m); ?>
<? endforeach; ?>