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
		<? foreach (@$offices as $office) : ?>
		<tr class="<?php echo 'row'.$m; ?>">
			<td>
				<span class="editlinktip hasTip" title="<?= @text('Edit Profile') ?>::<?= @$escape($office->title); ?>">
					<a href="<?= @route('view=office&id='.$office->id); ?>">
						<?= @$escape($office->title); ?>
					</a>
				</span>
			</td>
			<td align="center" width="1%">
				<?= $office->people; ?>
			</td>
		</tr>
		<? $i = $i + 1; $m = (1 - $m); ?>
		<? endforeach; ?>

		<? if (!count(@$offices)) : ?>
		<tr>
			<td colspan="8" align="center">
				<?= @text('No items found'); ?>
			</td>
		</tr>
		<? endif; ?>
	</tbody>
</table>

