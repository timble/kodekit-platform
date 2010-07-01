<? /** $Id: default_people.php 497 2010-05-13 01:25:03Z johanjanssens $ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<table class="adminlist" style="clear: both;">
	<thead>
		<tr>
			<th>
				<?= @text('Title'); ?>
			</th>
		</tr>
	</thead>
	<tbody>	
		<? $i = 0; $m = 0; ?>
		<? foreach (@$people as $person) : ?>
		<tr class="<?php echo 'row'.$m; ?>">
			<td>
				<span class="editlinktip hasTip" title="<?= @text('Edit Profile') ?>::<?= @escape($person->name); ?>">
					<a href="<?= @route('view=person&id='.$person->id); ?>">
						<?= @escape($person->name); ?>
					</a>
				</span>
			</td>
		</tr>
		<? $i = $i + 1; $m = (1 - $m); ?>
		<? endforeach; ?>

		<? if (!count(@$people)) : ?>
		<tr>
			<td colspan="8" align="center">
				<?= @text('No items found'); ?>
			</td>
		</tr>
		<? endif; ?>
	</tbody>
</table>