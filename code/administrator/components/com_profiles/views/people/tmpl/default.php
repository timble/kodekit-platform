<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @style(@$mediaurl.'/com_profiles/css/grid.css'); ?>
<? @style(@$mediaurl.'/com_profiles/css/admin.css'); ?>

<? $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'this.form.submit();');?>

<form action="<?= @route()?>" method="get" class="form-filters">
	<input type="hidden" name="option" value="com_profiles" />
	<input type="hidden" name="view" value="people" />
	
	<div class="filter-search">
		<?= @template('filter_name'); ?>
	</div>
</form>

<form action="<?= @route()?>" method="post" name="adminForm" class="form-grid">
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="action" value="browse" />
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
					<?= @helper('grid.sort', 'Name', 'lastname', @$state->direction, @$state->order); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'Department', 'department', @$state->direction, @$state->order); ?><br/>
				</th>
				<th>
					<?= @helper('grid.sort', 'Office', 'office', @$state->direction, @$state->order); ?><br/>
				</th>
				<th>
					<?= @helper('grid.sort', 'User', 'user_name', @$state->direction, @$state->order); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'Enabled', 'enabled', @$state->direction, @$state->order); ?><br/>
					
				</th>
				<th>
					<?= @helper('grid.sort', 'Hits', 'hits', @$state->direction, @$state->order); ?><br/>
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
				<td align="center">
					<?= @helper('admin::com.profiles.helper.select.departments', @$state->profiles_department_id, 'profiles_department_id', $attribs, '', true) ?>
				</td>
				<td align="center">
					<?= @helper('admin::com.profiles.helper.select.offices', @$state->profiles_office_id, 'profiles_office_id', $attribs, '', true) ?>
				</td>
				<td align="center">
					<?= @helper('admin::com.profiles.helper.select.enabled',  @$state->enabled ); ?>
				</td>
				<td>
				</td>
				<td>
				</td>
			</tr>
		</thead>
		<tbody>
		<? if (count(@$people)) : ?>
			<?= @template('default_people'); ?>
		<? else : ?>
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