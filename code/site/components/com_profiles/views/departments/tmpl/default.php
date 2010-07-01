<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<script src="media://lib_koowa/js/koowa.js" />

<div class="joomla ">
	<form action="<?= @route()?>" method="post" name="adminForm">
	<input type="hidden" name="action" value="browse" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?= $state->order; ?>" />
	<input type="hidden" name="filter_direction" value="<?= $state->direction; ?>" />
	<h3><?=@text('Departments');?></h3>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tfoot>
			<tr>
				<td align="center" colspan="6" class="sectiontablefooter">
					<?= @helper('paginator.pagination', array('total' => $total)) ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td class="sectiontableheader" width="5" align="center">
					<?= @text('NUM'); ?>
				</td>
				<td class="sectiontableheader" align="left">
					<?= @helper('grid.sort', array('column' => 'title', 'title' => 'Department')); ?>
				</td>
				<td class="sectiontableheader" align="left">
					<?= @helper('grid.sort', array('column' => 'people')); ?>
				</td>
			</tr>
			<?= @template('default_items'); ?>
		</tbody>
	</table>
	</form>
</div>