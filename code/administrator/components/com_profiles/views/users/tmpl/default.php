<? /** $Id: form.php 234 2009-09-30 01:40:02Z johan $ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<style src="media://com_default/css/admin.css" />
<style src="media://com_profiles/css/admin.css" />

<table class="adminlist" style="clear: both;">
<thead>
	<form action="<?= @route()?>" method="get">
	<input type="hidden" name="option" value="com_profiles" />
	<input type="hidden" name="view" value="users" />
	<tr>
		<th width="5">
			<?= @text('NUM'); ?>
		</th>
		<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count($users); ?>);" />
		</th>
		<th>
			<?= @helper('grid.sort', array('column' => 'name')); ?>
		</th>
		<th>
			<?= @helper('grid.sort', array('column' => 'username')); ?>
		</th>
		<th>
			<?= @helper('grid.sort', array('column' => 'enabled')); ?><br/>
		</th>
		<th>
			<?= @helper('grid.sort', array('column' => 'usertype', 'title' => 'Group')); ?>
		</th>
		<th>
			<?= @helper('grid.sort', array('column' => 'email')); ?>
		</th>
		<th>
			<?= @helper('grid.sort', array('column' => 'lastvisitDate', 'title' => 'Last Visit')); ?>
		</th>
	</tr>
	<tr>
		<td colspan="2">
			<?= @text('Filters'); ?>	
		</td>
		<td colspan="2">
			<?= @template('admin::com.default.view.list.search_form'); ?>
		</td>
		<td align="center">
			<?= @helper('admin::com.profiles.helper.listbox.enabled',  array('attribs' => array('onchange' => 'this.form.submit();'))); ?>
		</td>
		<td align="center">
			<?= @helper('admin::com.profiles.helper.listbox.groups',  array('attribs' => array('onchange' => 'this.form.submit();'))); ?>
		</td>
		<td>
		</td>
	</tr>
	</form>
</thead>
<tbody>
	<? if (count($users)) : ?>
		<?= @template('default_users'); ?>
	<? else : ?>
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
			<?= @helper('admin::com.default.helper.paginator.pagination', array('total' => $total)) ?>
		</td>
	</tr>
</tfoot>
</table>