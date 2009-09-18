<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @style(@$mediaurl.'/com_beer/css/grid.css') ?>
<? @style(@$mediaurl.'/com_beer/css/beer_admin.css') ?>

<form action="<?= @route()?>" method="get">
	<input type="hidden" name="option" value="com_beer" />
	<input type="hidden" name="view" value="departments" />

	<table>
		<tr>
			<td align="left" width="100%">
				<?=@text('Filter')?>:
				<input name="search" id="search" value="<?= @$state->search?>" />
				<button onclick="this.form.submit();"><?= @text('Go')?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('enabled').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?= @helper('admin::com.beer.helper.select.enabled',  @$state->enabled ); ?>
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
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count(@$departments); ?>);" />
				</th>
				<th>
					<?= @helper('grid.sort', 'Title', 'title', @$state->direction, @$state->order); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'Enabled', 'enabled', @$state->direction, @$state->order); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'People', 'people', @$state->direction, @$state->order); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'ID', 'beer_department_id', @$state->direction, @$state->order); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<? $i = 0; $m = 0; ?>
		<? foreach (@$departments as $department) : ?>
			<tr class="<?php echo 'row'.$m; ?>">
				<td align="center">
					<?= $i + 1; ?>
				</td>
				<td align="center">
					<? // @helper('grid.checkedOut', $project, $i, $project->id); ?>
					<?= @helper('grid.id', $i, $department->id); ?>
				</td>
				<td>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit Profile' );?>::<?= @$escape($department->title); ?>">
						<a href="<?= @route('view=department&id='.$department->id); ?>">
							<?= @$escape($department->title); ?>
						</a>
					</span>
				</td>
				<td align="center" width="15px">
					<?= @helper('grid.enable', $department->enabled, $i) ?>
				</td>
				<td align="center" width="1%">
					<?= $department->people; ?>
				</td>
				<td align="center" width="1%">
					<?= $department->id; ?>
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