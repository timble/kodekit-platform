<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @style(@$mediaurl.'/com_profiles/css/grid.css'); ?>
<? @style(@$mediaurl.'/com_profiles/css/admin.css'); ?>

<form action="<?= @route()?>" method="post" name="adminForm">
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="action" value="browse" />
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
					<?= @helper('grid.sort', 'ID', 'profiles_office_id', @$state->direction, @$state->order); ?>
				</th>
			</tr>
			<tr>
				<td colspan="2">
					<?= @text('Filters'); ?>	
				</td>
				<td>
					<input name="search" id="search" value="<?= @$state->search;?>" />
					<button onclick="this.form.submit();"><?= @text('Go')?></button>
					<button onclick="document.getElementById('search').value='';this.form.submit();"><?= @text('Reset'); ?></button>
				</td>
				<td>
					<?= @helper('admin::com.profiles.helper.select.enabled',  @$state->enabled ); ?>
				</td>
				<td>	
				</td>
				<td>
				</td>
			</tr>
		</thead>
		<tbody>
		
		<?= @template('default_items'); ?>

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
					<?= @helper('admin::com.default.helper.paginator.pagination', @$total, @$state->offset, @$state->limit) ?>
				</td>
			</tr>
		</tfoot>
	</table>
</form>