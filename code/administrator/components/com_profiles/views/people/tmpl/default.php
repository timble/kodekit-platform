<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<style src="media://com_default/css/admin.css" />
<style src="media://com_profiles/css/admin.css" />

<div style="margin-bottom: 25px">
	<div style="float: right">
		<?= @template('admin::com.default.view.list.search_letters'); ?>
	</div>
</div>

<table class="adminlist" style="clear: both;">

<thead>
	<form action="<?= @route()?>" method="get">
	<input type="hidden" name="option" value="com_profiles" />
	<input type="hidden" name="view" value="people" />
	<tr>
		<th width="5">
			<?= @text('NUM'); ?>
		</th>
		<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count($people); ?>);" />
		</th>
		<th>
			<?= @helper('grid.sort', array('column' => 'lastname', 'title' => 'Name')); ?>
		</th>
		<th>
			<?= @helper('grid.sort', array('column' => 'department')); ?><br/>
		</th>
		<th>
			<?= @helper('grid.sort', array('column' => 'office')); ?><br/>
		</th>
		<th>
			<?= @helper('grid.sort', array('column' => 'user_name', 'title' => 'Name')); ?>
		</th>
		<th>
			<?= @helper('grid.sort', array('column' => 'enabled')); ?><br/>
		</th>
		<th>
			<?= @helper('grid.sort', array('column' => 'hits')); ?><br/>
		</th>
	</tr>
	<tr>
		<td colspan="2">
			<?= @text('Filters'); ?>	
		</td>
		<td>
			<?= @template('admin::com.default.view.list.search_form'); ?>
		</td>
		<td align="center">
			<?= @helper('admin::com.profiles.helper.listbox.departments', array('attribs' => array('onchange' => 'this.form.submit();'))); ?>
		</td>
		<td align="center">
			<?= @helper('admin::com.profiles.helper.listbox.offices', array('attribs' => array('onchange' => 'this.form.submit();'))); ?>
		</td>
		<td>
		</td>
		<td align="center">
			<?= @helper('admin::com.profiles.helper.listbox.enabled',  array('attribs' => array('onchange' => 'this.form.submit();'))); ?>
		</td>
		<td>
		</td>
	</tr>
	</form>
</thead>

<tbody>
	<? if (count($people)) : ?>
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
			<?= @helper('admin::com.default.helper.paginator.pagination', array('total' => $total)) ?>
		</td>
	</tr>
</tfoot>
</table>