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
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count(@$people); ?>);" />
				</th>
				<th>
					<?= @helper('grid.sort', 'Firstname', 'firstname', @$filter['direction'], @$filter['order']); ?>
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
		<? foreach (@$people as $person) : ?>
			<tr class="<?php echo 'row'.$m; ?>">
				<td align="center">
					<?= $i + 1; ?>
				</td>
				<td align="center">
					<? // @helper('grid.checkedOut', $project, $i, $project->id); ?>
					<?= @helper('grid.id', $i, $person->id); ?>
				</td>
				<td>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Edit Profile' );?>::<?= @$escape($person->firstname); ?>">
						<a href="<?= @route('view=person&layout=form&id='.$person->id); ?>">
							<?= @$escape($person->firstname); ?>
						</a>
					</span>
				</td>
				<td align="center" width="15px">
					<?= @helper('grid.enable', $person->enabled, $i) ?>
				</td>
				<td align="center" width="1%">
					<?= $person->id; ?>
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
		<tfoot>
			<tr>
				<td colspan="20">
					<?= @$pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>
</form>