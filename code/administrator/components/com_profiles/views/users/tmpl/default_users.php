<? /** $Id: form.php 216 2009-09-20 03:33:11Z johan $ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? $i = 0; $m = 0; ?>
<? foreach (@$users as $user) : ?>
<tr class="<?= 'row'.$m; ?>">
	<td align="center">
		<?= $i + 1; ?>
	</td>
	<td align="center">
		<?= @helper('grid.id', $i, $user); ?>
	</td>
	<td>
		<span class="editlinktip hasTip" title="<?= @text('Edit User')?>::<?= @$escape($user->name); ?>">
			<?= @$escape($user->name)?>
		</span>
	</td>
	<td width="15%">
		<?= $user->username ?>
	</td>
	<td width="5%">
		<?= @helper('grid.enable', !$user->block, $i)?>
	</td>
	<td width="15%">
		<?= $user->usertype ?>
	</td>
	<td width="15%">
		<?= $user->email ?>
	</td>
	<td width="10%">
		<?= ($user->lastvisitDate == '0000-00-00 00:00:00') ? @text('Never') : @helper('date', $user->lastvisitDate, '%Y-%m-%d %H:%M:%S') ?>
	</td>
</tr>
<? $i = $i + 1; $m = (1 - $m);?>
<? endforeach; ?>		