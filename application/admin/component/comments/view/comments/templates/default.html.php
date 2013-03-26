<style src="media://comments/css/comments-default.css" />

<div id="list" class="-koowa-box-flex">
	<form action="<?= @route()?>" method="post" name="adminForm">
		<table class="adminlist" style="clear: both;">
			<thead>
				<tr>
					<th width="20">
						<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count($comments); ?>);" />
					</th>
					<th>
						<?= @helper('grid.sort', array('column' => 'table')); ?>
					</th>
					<th>
						<?= @helper('grid.sort', array('column' => 'row', 'title' => 'Ticket ID')); ?>
					</th>
					<th>
						<?= @helper('grid.sort', array('column' => 'created_by')); ?>
					</th>
					<th>
						<?= @helper('grid.sort', array('column' => 'text')); ?>
					</th>
				</tr>
			</thead>
			<tbody>
			<? if (count($comments)) : ?>
				<?= @template('default_comments.html'); ?>
			<? else : ?>
				<tr>
					<td colspan="5" align="center">
						<?= @text('No items found'); ?>
					</td>
				</tr>
			<? endif; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="20">
						<?= @helper('com:application.paginator.pagination', array('total' => $total)) ?>
					</td>
				</tr>
			</tfoot>
		</table>
	</form>
</div>