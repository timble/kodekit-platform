<? /** $Id: form.php 234 2009-09-30 01:40:02Z johan $ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @style(@$mediaurl.'/com_profiles/css/grid.css'); ?>
<? @style(@$mediaurl.'/com_profiles/css/admin.css'); ?>

<? $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'this.form.submit();');?>

<form action="<?= @route()?>" method="get" class="form-filters">
	<input type="hidden" name="option" value="com_profiles" />
	<input type="hidden" name="view" value="users" />
	
	<table>
		<tr>
			<td align="left" width="100%">
				<?= @text('Search')?>:
				<input name="search" id="search" value="<?= @$state->search;?>" />
				<button onclick="this.form.submit();"><?= @text('Go'); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.getElementById('enabled').value='';this.form.submit();"><?= @text('Reset'); ?></button>
			</td>
			<td nowrap="nowrap">
				<?= @helper('admin::com.profiles.helper.filter.groups', @$state->gid, 'gid', $attribs, '', true) ?>
			</td>
		</tr>
	</table>
</form>

<form action="<?= @route()?>" method="post" name="adminForm" class="form-grid">
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="action" value="" />
	<table class="adminlist" style="clear: both;">
		<thead>
			<tr>
				<th width="5">
					<?= @text('NUM'); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count(@$users); ?>);" />
				</th>
				<th>
					<?= @helper('grid.sort', 'Name', 'name', @$state->direction, @$state->order); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'Username', 'username', @$state->direction, @$state->order); ?>
				</th>
				<th>Logged In</th>
				<th>
					<?= @helper('grid.sort', 'Enabled', 'enabled', @$state->direction, @$state->order); ?><br/>
					<?= @helper('admin::com.profiles.helper.select.enabled',  @$state->enabled ); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'Group', 'usertype', @$state->direction, @$state->order); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'E-Mail', 'email', @$state->direction, @$state->order); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'Last Visit', 'lastvisitDate', @$state->direction, @$state->order); ?>
				</th>
				<th>
					<?= @helper('grid.sort', 'ID', 'id', @$state->direction, @$state->order); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		
		<?= @template('form_items'); ?>
			
		<? if (!count(@$users)) : ?>
		<tr>
			<td colspan="10" align="center">
				<?= @text('No items found'); ?>
			</td>
		</tr>
		<? endif; ?>
		</tbody>
				
		<tfoot>
			<tr>
				<td colspan="20">
					<?= @helper('admin::com.koowa.helper.paginator.pagination', @$total, @$state->offset, @$state->limit) ?>
				</td>
			</tr>
		</tfoot>
	</table>
</form>