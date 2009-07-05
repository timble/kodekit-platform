<? /** $Id$ */ ?>
<? defined('_JEXEC') or die('Restricted access'); ?>

<? @style(@$mediaurl.'/com_beer/css/grid.css') ?>
<? @style(@$mediaurl.'/com_beer/css/beer_admin.css') ?>

<form action="<?= @route()?>" method="post" name="adminForm">
	<input type="hidden" name="action" value="browse" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?= @$filter['order']?>" />
	<input type="hidden" name="filter_direction" value="<?= @$filter['direction']?>" />

	<table>
		<tr>
			<td align="left" width="100%">
				<?=@text('Filter')?>:
				<input type="text" name="search" id="search" value="<?= @$filter['search']?>" class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"><?= @text('Go')?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('enabled').value='';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
			</td>
			<td nowrap="nowrap">
				<?= @helper('admin::com.beer.helper.select.enabled',  @$filter['enabled'] ); ?>
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
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count(@$departments); ?>);" />
				</th>
				<th>
					<?= @helper('grid.sort', 'Title', 'title', @$filter['direction'], @$filter['order']); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'Enabled', 'enabled', @$filter['direction'], @$filter['order']); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'People', 'people', @$filter['direction'], @$filter['order']); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'ID', 'beer_profile_id', @$filter['direction'], @$filter['order']); ?>
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
						<a href="<?= @route('view=department&layout=form&id='.$department->id); ?>">
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
					<?= @$pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>
</form>