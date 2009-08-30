<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @style(@$mediaurl.'/com_beer/css/grid.css'); ?>
<? @style(@$mediaurl.'/com_beer/css/beer_admin.css'); ?>

<form action="<?= @route()?>" method="post" name="adminForm">
	<input type="hidden" name="action" value="browse" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?= @$state->order; ?>" />
	<input type="hidden" name="filter_direction" value="<?= @$state->direction; ?>" />

	<table>
		<tr>
			<td align="left" width="100%">
				<?= @text('Filter')?>:
				<input type="text" name="search" id="search" value="<?= @$state->search;?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('enabled').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?= @helper('admin::com.beer.helper.select.enabled',  @$state->enabled ); ?>
			</td>
		</tr>
	</table>

	<table class="adminlist" style="clear: both;">
		<thead>
			<tr>
				<th width="5">
					<?= @text('NUM'); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count(@$offices); ?>);" />
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
					<?= @helper('grid.sort', 'ID', 'beer_office_id', @$state->direction, @$state->order); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<? $i = 0; $m = 0; ?>
		<? foreach (@$offices as $office) : ?>
			<tr class="<?= 'row'.$m?>">
				<td align="center">
					<?= $i + 1; ?>
				</td>
				<td align="center">
					<? // @helper('grid.checkedOut', $project, $i, $project->id); ?>
					<?= @helper('grid.id', $i, $office->id); ?>
				</td>
				<td>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit Profile' );?>::<?= @$escape($office->title); ?>">
						<a href="<?= @route('view=office&layout=form&id='.$office->id); ?>">
							<?= @$escape($office->title); ?>
						</a>
					</span>
				</td>
				<td align="center" width="15px">
					<?= @helper('grid.enable', $office->enabled, $i) ?>
				</td>
				<td align="center" width="1%">
					<?= $office->people; ?>
				</td>
				<td align="center" width="1%">
					<?= $office->id; ?>
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
		<tfoot>
			<tr>
				<td colspan="20">
					<?= @helper('pagination.limit', @$state->limit) ?>
					<?= @helper('pagination.pages', @$total, @$state->offset, @$state->limit) ?>
				</td>
			</tr>
		</tfoot>
	</table>
</form>