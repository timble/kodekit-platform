<? defined('_JEXEC') or die('Restricted access'); ?>

<? @style(@$mediaurl.'/com_beer/css/grid.css'); ?>
<? @style(@$mediaurl.'/com_beer/css/beer_admin.css'); ?>

<form action="<?= @route()?>" method="post" name="adminForm">
	<input type="hidden" name="action" value="browse" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?= @$filter['order']; ?>" />
	<input type="hidden" name="filter_direction" value="<?= @$filter['direction']; ?>" />

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
					<?= @helper('grid.sort', 'Title', 'title', @$filter['direction'], @$filter['order']); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'Enabled', 'enabled', @$filter['direction'], @$filter['order']); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'ID', 'beer_profile_id', @$filter['direction'], @$filter['order']); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		<? $i = 0; $m = 0; ?>
		<? foreach (@$offices as $office) : ?>
			<tr class="<?php echo 'row'.$m; ?>">
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
					<?= @$pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>
</form>