<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<table class="adminlist" style="clear: both;">
	<thead>
		<tr>
			<th>
				<?= @text('Title'); ?>
			</th>
			<th>
				<?= @text('People'); ?>
			</th>
		</tr>
	</thead>
	<tbody>	
		<? $i = 0; $m = 0; ?>
		<? foreach (@$departments as $department) : ?>
		<tr class="<?php echo 'row'.$m; ?>">
			<td>
				<span class="editlinktip hasTip" title="<?= @text('Edit Profile') ?>::<?= @$escape($department->title); ?>">
					<a href="<?= @route('view=department&id='.$department->id); ?>">
						<?= @$escape($department->title); ?>
					</a>
				</span>
			</td>
			<td align="center" width="1%">
				<?= $department->people; ?>
			</td>
		</tr>
		<? $i = $i + 1; $m = (1 - $m); ?>
		<? endforeach; ?>

		<? if (!count(@$departments)) : ?>
		<tr>
			<td colspan="8" align="center">
				<?= @text('No items found'); ?>
			</td>
		</tr>
		<? endif; ?>
	</tbody>
</table>

