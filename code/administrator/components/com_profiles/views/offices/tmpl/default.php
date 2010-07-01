<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<style src="media://com_default/css/admin.css" />
<style src="media://com_profiles/css/admin.css" />

<table class="adminlist" style="clear: both;">

<thead>
	<form action="<?= @route()?>" method="get"">
	<input type="hidden" name="option" value="com_profiles" />
	<input type="hidden" name="view" value="offices" />	
	<tr>
		<th width="5">
			<?= @text('NUM'); ?>
		</th>
		<th width="20">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count($offices); ?>);" />
		</th>
		<th>
			<?= @helper('grid.sort', array('column' => 'title')); ?>
		</th>
		<th>
			<?= @helper('grid.sort', array('column' => 'enabled')); ?>
		</th>
		<th>
			<?= @helper('grid.sort', array('column' => 'people')); ?>
		</th>
	</tr>
	<tr>
		<td colspan="2">
			<?= @text('Filters'); ?>	
		</td>
		<td>
			<?= @template('admin::com.default.view.list.search_form'); ?>
		</td>
		<td>
			<?= @helper('admin::com.profiles.helper.listbox.enabled',  array('attribs' => array('onchange' => 'this.form.submit();'))); ?>
		</td>
		<td>	
		</td>
	</tr>
	</form>
</thead>

<tbody>
	<? if (count($offices)) : ?>
		<?= @template('default_offices'); ?>
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