<? /** $Id: form.php 197 2009-09-18 19:48:40Z johan $ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? $i = 0; $m = 0; ?>
<? foreach (@$offices as $office) : ?>
<tr class="<?= 'row'.$m?>">
	<td align="center">
		<?= $i + 1; ?>
	</td>
	<td align="center">
		<?= @helper('grid.id', $i, $office); ?>
	</td>
	<td>
		<span class="editlinktip hasTip" title="<?= @text('Edit Profile');?>::<?= @$escape($office->title); ?>">
		<? if($office->locked) : ?>
			<span>
				<?= @$escape($office->title); ?>
			</span>
		<? else : ?>
			<a href="<?= @route('view=office&id='.$office->id); ?>">
				<?= @$escape($office->title); ?>
			</a>
		<? endif; ?>
		</span>
	</td>
	<td align="center" width="15px">
		<?= @helper('grid.enable', $office->enabled, $i) ?>
	</td>
	<td align="center" width="1%">
		<?= $office->people; ?>
	</td>
</tr>
<? $i = $i + 1; $m = (1 - $m); ?>
<? endforeach; ?>

		