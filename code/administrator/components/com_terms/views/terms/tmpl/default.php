<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<style src="media://com_default/css/admin.css" />
<style src="media://com_terms/css/admin.css" />

<div style="margin-bottom: 25px">
	<div style="float: left">
		<?= @template('admin::com.default.view.list.search_form'); ?>
	</div>
</div>

<form action="<?= @route()?>" method="post" name="adminForm">
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="action" value="browse" />
	<table class="adminlist" style="clear: both;">
		<thead>
			<tr>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count($terms); ?>);" />
				</th>
				<th>
					<?= @helper('grid.sort', array('column' => 'title')); ?>
				</th>
				<th>
					<?= @helper('grid.sort', array('column' => 'slug')); ?>
				</th>
				<th>
					<?= @helper('grid.sort', array('column' => 'count')); ?>
				</th>
			</tr>
			<tr>
				<td colspan="2">
					<?= @text('Filters'); ?>	
				</td>
				<td>
				</td>
			</tr>
		</thead>
		<tbody>
		<? if (count($terms)) : ?>
			<?= @template('default_terms'); ?>
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
</form>