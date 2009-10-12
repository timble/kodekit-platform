<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<form action="<?= @route()?>" method="post" name="adminForm">
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="action" value="" />
	<table class="adminlist" style="clear: both;">
		<thead>
			<tr>
				<th width="5">
					<?= @text('NUM'); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count(@$tags); ?>);" />
				</th>
				<th>
					<?= @helper('grid.sort', 'Name', 'name', @$state->direction, @$state->order); ?>
				</th>
			</tr>
		</thead>
		<tbody>
		
		<? $i = 0; $m = 0; ?>
		<? foreach (@$tags as $tag) : ?>
		<tr class="<?= 'row'.$m?>">
			<td align="center">
				<?= $i + 1; ?>
			</td>
			<td align="center">
				<? // @helper('grid.checkedOut', $tag, $i, $tag->id); ?>
				<?= @helper('grid.id', $i, $tag->id); ?>
			</td>
			<td align="center">
				<?= $tag->name; ?>
			</td>
		</tr>
		<? $i = $i + 1; $m = (1 - $m); ?>
		<? endforeach; ?>

		<? if (!count(@$tags)) : ?>
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
					<?= @helper('admin::com.beer.helper.paginator.pagination', @$total, @$state->offset, @$state->limit) ?>
				</td>
			</tr>
		</tfoot>
	</table>
</form>