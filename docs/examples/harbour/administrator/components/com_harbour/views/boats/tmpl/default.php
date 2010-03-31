<? defined('KOOWA') or die; ?>
<form action="<?= @route(); ?>" method="get">
	<input type="hidden" name="option" value="com_harbour" />
	<input type="hidden" name="view" value="boats" />
	<fieldset>
	<legend><?= @text('Filters'); ?></legend> 
    	<table>
        	<tr>
            	<td align="left" width="100%">
                	<?= @text('SEARCH'); ?>
                	<input id="search" name="search" value="<?= @$state->search; ?>" />
                	<button onclick="this.form.submit();"><?= @text('SEARCH'); ?></button>
                	<button onclick="document.getElementById('search').value='';this.form.submit();"><?= @text('RESET'); ?></button>
            	</td>
        	</tr>
    	</table>
 	</fieldset>
 </form>

<form action="<?= @route()?>" method="post" name="adminForm">
	<input type="hidden" name="id" value="" />
	<input type="hidden" name="action" value="" />
	<table class="adminlist"  style="clear: both;">
		<thead>
			<tr>
				<th width="5">
					<?= @text('NUM'); ?>
				</th>
				<th width="20">
					<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count(@$boats); ?>);" />
				</th>
				<th>
					<?= @helper('grid.sort', 'Name', 'name', @$state->direction, @$state->order); ?>
				</th>
				<th width="30">
					<?= @helper('grid.sort', 'Enabled', 'enabled', @$state->direction, @$state->order); ?>
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
					<?= @helper('grid.id', $i, $boat); ?>
				</td>
				<td align="left">
					<a href="<?= @route('view=boat&id='.$boat->id); ?>">
    					<?=$boat->name?>
    				</a>
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
                     <?= @helper('admin::com.default.helper.paginator.pagination', @$total, @$state->offset, @$state->limit) ?>
                </td>
            </tr>
        </tfoot>
	</table>
</form>