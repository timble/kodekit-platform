<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @script(@$mediaurl.'/plg_koowa/js/koowa.js'); ?>

<div class="joomla ">
	<form action="<?= @route()?>" method="post" name="adminForm">
	<input type="hidden" name="action" value="browse" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?= @$state->order; ?>" />
	<input type="hidden" name="filter_direction" value="<?= @$state->direction; ?>" />
	<h3><?=@text('Departments');?></h3>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tfoot>
			<tr>
				<td align="center" colspan="6" class="sectiontablefooter">
					<?= @helper('pagination.limit', @$state->limit) ?>
					<?= @helper('pagination.pages', @$total, @$state->offset, @$state->limit) ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td class="sectiontableheader" width="5" align="center">
					<?= @text('NUM'); ?>
				</td>
				<td class="sectiontableheader" align="left">
					<?= @helper('grid.sort', 'Department', 'title', @$state->direction, @$state->order); ?>
				</td>
				<td class="sectiontableheader" align="left">
					<?= @helper('grid.sort', 'People', 'people', @$state->direction, @$state->order); ?>
				</td>
			</tr>
			<?php echo $this->loadTemplate('items'); ?>
		</tbody>
	</table>
	</form>
</div>