<? /** $Id: form.php 216 2009-09-20 03:33:11Z johan $ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? $i = 0; $m = 0; ?>
<? foreach (@$people as $person) : ?>
<tr class="<?= 'row'.$m; ?>">
	<td align="center">
		<?= $i + 1; ?>
	</td>
	<td align="center">
		<? // @helper('grid.checkedOut', $project, $i, $project->id); ?>
		<?= @helper('grid.id', $i, $person->id); ?>
	</td>
	<td>
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit Profile' );?>::<?= @$escape($person->name); ?>">
			<a href="<?= @route('view=person&id='.$person->id)?>">
				<?= @$escape($person->name)?>
			</a>
		</span>
	</td>
	<td align="center">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit Department' );?>::<?= @$escape($person->department); ?>">
			<a href="<?= @route('view=department&id='.$person->beer_department_id)?>">
				<?= @$escape($person->department)?>
			</a>
		</span>
	</td>
	<td align="center">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit Office' );?>::<?= @$escape($person->office); ?>">
			<a href="<?= @route('view=office&id='.$person->beer_office_id)?>">
				<?= @$escape($person->office)?>
			</a>
		</span>
	</td>
	<td align="center">
		<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit Profile' );?>::<?= @$escape($person->user_name); ?>">
			<a href="<?= @route('option=com_users&task=edit&view=user&cid[]='.$person->user_id)?>">
				<?= @$escape($person->user_name)?>
			</a>
		</span>
	</td>
	<td align="center" width="15px">
		<?= @helper('grid.enable', $person->enabled, $i)?>
	</td>
	<td align="center" width="1%">
		<?= $person->id?>
	</td>
</tr>
<? $i = $i + 1; $m = (1 - $m);?>
<? endforeach; ?>		