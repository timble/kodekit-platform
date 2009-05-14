
<form action="<?= @route()?>" method="post" name="adminForm">
	<input type="hidden" name="order_change" value="0" />
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?= @$filter['order']; ?>" />
	<input type="hidden" name="filter_direction" value="<?= @$filter['direction']; ?>" />
 

	<table class="adminlist">
	
		<thead>
			<tr>
				<th width="5">
					<?= @text('NUM'); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count(@$boats); ?>);" />
				</th>
				<th>
					<?= @helper('grid.sort', 'Name', 'name', @$filter['direction'], @$filter['order']); ?>
				</th>
				<th width="30">
					<?= @helper('grid.sort', 'Enabled', 'enabled', @$filter['direction'], @$filter['order']); ?>
				</th>
			</tr>
		</thead>
		
		<tbody>
			<? $i = 0;?>
			<? foreach (@$boats as $boat) : ?>
			<tr>
				<td align="center">
					<?= $i + 1; ?>
				</td>
				<td align="center">
					<?= @helper('grid.id', $i, $boat->id); ?>
				</td>
				<td align="left">
					<?= $boat->name; ?>
				</td>
				<td align="center">
                   	<?= @helper('grid.enable', $boat->enabled, $i ); ?>
                </td>
			</tr>
			<? ++$i?>
			<? endforeach; ?>

			<? if (!count(@$boats)) : ?>
			<tr>
				<td colspan="20" align="center">
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