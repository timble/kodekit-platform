<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @style(@$mediaurl.'/com_beer/css/grid.css'); ?>
<? @style(@$mediaurl.'/com_beer/css/beer_admin.css'); ?>

<form action="<?= @route()?>" method="get">
	<input type="hidden" name="option" value="com_beer" />
	<input type="hidden" name="view" value="people" />

	<table>
		<tr>
			<td align="left" width="100%">
				<?= @text('Filter'); ?>:
				<input name="search" id="search" value="<?= @$state->search;?>" />
				<button onclick="this.form.submit();"><?= @text('Go')?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('beer_department_id').value='';this.form.getElementById('beer_office_id').value='';this.form.getElementById('enabled').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<? $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'this.form.submit();');?>
				<?=@helper('admin::com.beer.helper.select.departments', @$state->beer_department_id, 'beer_department_id', $attribs, '', true) ?>
				<?=@helper('admin::com.beer.helper.select.offices', @$state->beer_office_id, 'beer_office_id', $attribs, '', true) ?>
				<?=@helper('admin::com.beer.helper.select.enabled',  @$state->enabled ); ?>
			</td>
		</tr>
	</table>
</form>

<form action="<?= @route()?>" method="post" name="adminForm">
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<table class="adminlist" style="clear: both;">
		<thead>
			<tr>
				<th width="5">
					<?= @text('NUM'); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count(@$people); ?>);" />
				</th>
				<th>
					<?= @helper('grid.sort', 'Name', 'firstname', @$state->direction, @$state->order); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'Department', 'department', @$state->direction, @$state->order); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'Office', 'office', @$state->direction, @$state->order); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'Enabled', 'enabled', @$state->direction, @$state->order); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'ID', 'beer_person_id', @$state->direction, @$state->order); ?>
				</th>
			</tr>
		</thead>
		<tbody>
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
				<td align="center" width="15px">
					<?= @helper('grid.enable', $person->enabled, $i)?>
				</td>
				<td align="center" width="1%">
					<?= $person->id?>
				</td>
			</tr>
		<? $i = $i + 1; $m = (1 - $m);?>
		<? endforeach; ?>

		<? if (!count(@$people)) : ?>
			<tr>
				<td colspan="8" align="center">
					<?= @text('No items found'); ?>
				</td>
			</tr>
		<? endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="20">
					<?= @helper('paginator.limit', @$state->limit) ?>
					<?= @helper('paginator.pages', @$total, @$state->offset, @$state->limit) ?>
				</td>
			</tr>
		</tfoot>
	</table>
</form>